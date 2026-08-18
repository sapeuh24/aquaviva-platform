import { reactive, ref } from 'vue'

const OWNER = 'sapeuh24'
const REPO = 'aquaviva-platform'
const BRANCH = 'gh-pages'
const FILE = 'db.json'
const LOCAL_KEY = '_aqdb'           // local copy of the data
const PENDING_KEY = '_aqdb_pending' // '1' when local changes were not pushed to GitHub yet

// Token leído en cada uso para soportar navegación SPA con ?token= en la URL inicial
function getToken() {
  const fromUrl = new URLSearchParams(window.location.search).get('token')
  if (fromUrl) {
    localStorage.setItem('_aqtoken', fromUrl)
    sessionStorage.setItem('_aqtoken', fromUrl)
    return fromUrl
  }
  return localStorage.getItem('_aqtoken') || sessionStorage.getItem('_aqtoken') || import.meta.env.VITE_GITHUB_TOKEN || ''
}

// Sin token no se envía el header Authorization, así la lectura anónima del repo público sigue funcionando
function authHeaders() {
  const token = getToken()
  return token ? { Authorization: `Bearer ${token}` } : {}
}

// Singleton state — shared across all views
const db = reactive({
  proyectos: [],
  compromisos: [],
  programas: [],
  acciones: [],
  avances: [],
  evidencias: [],
  indicadores: [],
  alertas: [],
  empresas: [],
})

const dbStatus = ref('idle') // 'idle' | 'loading' | 'saving' | 'pending' | 'error'
let _sha = null
let _loaded = false
let _syncing = false
let _syncQueued = false

function persistLocal() {
  try {
    localStorage.setItem(LOCAL_KEY, JSON.stringify(db))
  } catch (e) {
    console.error('[db] localStorage write failed', e)
  }
}

function readLocal() {
  try {
    const raw = localStorage.getItem(LOCAL_KEY)
    return raw ? JSON.parse(raw) : null
  } catch {
    return null
  }
}

async function fetchRemote() {
  const r = await fetch(
    `https://api.github.com/repos/${OWNER}/${REPO}/contents/${FILE}?ref=${BRANCH}&t=${Date.now()}`,
    { headers: { ...authHeaders(), Accept: 'application/vnd.github.v3+json' } }
  )
  if (!r.ok) throw new Error(`HTTP ${r.status}`)
  const data = await r.json()
  const parsed = JSON.parse(decodeURIComponent(escape(atob(data.content.replace(/\n/g, '')))))
  return { sha: data.sha, parsed }
}

/**
 * Load: hydrate from localStorage first (instant, works offline), then reconcile
 * with db.json on GitHub. If local changes are still pending, local wins and is
 * pushed to the remote.
 */
async function loadDb() {
  if (_loaded) return

  const local = readLocal()
  if (local) Object.assign(db, local)

  if (local && localStorage.getItem(PENDING_KEY) === '1') {
    // Unpushed changes: local wins, feed db.json on GitHub
    _loaded = true
    syncToGitHub()
    return
  }

  if (!local) dbStatus.value = 'loading'
  try {
    const { sha, parsed } = await fetchRemote()
    _sha = sha
    Object.assign(db, parsed)
    persistLocal()
    dbStatus.value = 'idle'
  } catch (e) {
    console.error('[db] load error', e)
    // Remote unavailable: keep using local data if we have it
    dbStatus.value = local ? 'idle' : 'error'
  } finally {
    _loaded = true
  }
}

/**
 * Save: ALWAYS write to localStorage first, then try to feed db.json on GitHub.
 * If the push fails the data stays safe locally, marked as pending, and is
 * retried on the next save or reload. Never throws.
 */
async function saveDb() {
  persistLocal()
  await syncToGitHub()
}

async function syncToGitHub() {
  if (_syncing) {
    _syncQueued = true
    return
  }
  _syncing = true
  dbStatus.value = 'saving'
  try {
    // Always fetch fresh SHA to avoid 409 conflicts on concurrent saves
    const check = await fetch(
      `https://api.github.com/repos/${OWNER}/${REPO}/contents/${FILE}?ref=${BRANCH}&t=${Date.now()}`,
      { headers: authHeaders() }
    )
    if (!check.ok) throw new Error(`HTTP ${check.status} fetching SHA`)
    const checkData = await check.json()
    _sha = checkData.sha

    const json = JSON.stringify(db, null, 2)
    const content = btoa(unescape(encodeURIComponent(json)))
    const res = await fetch(`https://api.github.com/repos/${OWNER}/${REPO}/contents/${FILE}`, {
      method: 'PUT',
      headers: { ...authHeaders(), 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: 'db: update demo data', content, sha: _sha, branch: BRANCH }),
    })
    if (!res.ok) {
      const err = await res.json().catch(() => ({}))
      throw new Error(err.message || `HTTP ${res.status}`)
    }
    const saved = await res.json()
    _sha = saved.content.sha
    localStorage.removeItem(PENDING_KEY)
    dbStatus.value = 'idle'
  } catch (e) {
    console.error('[db] sync error — data kept locally, push pending', e)
    localStorage.setItem(PENDING_KEY, '1')
    dbStatus.value = 'pending'
  } finally {
    _syncing = false
    if (_syncQueued) {
      _syncQueued = false
      syncToGitHub()
    }
  }
}

function nextId(arr) {
  return arr.length ? Math.max(...arr.map(x => Number(x.id) || 0)) + 1 : 1
}

export function useDb() {
  return { db, dbStatus, loadDb, saveDb, nextId }
}
