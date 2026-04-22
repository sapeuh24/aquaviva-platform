<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bienvenido a Aquaviva Platform</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0B1512; font-family: -apple-system, Arial, Helvetica, sans-serif;">

{{-- Wrapper externo --}}
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0B1512; margin: 0; padding: 0;">
    <tr>
        <td align="center" style="padding: 32px 16px;">

            {{-- Contenedor principal 600px --}}
            <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%; margin: 0 auto;">

                {{-- ===== HEADER ===== --}}
                <tr>
                    <td style="background-color: #0A3D26; border-radius: 12px 12px 0 0; padding: 36px 40px 32px 40px; text-align: center;">

                        {{-- Logo / Nombre estilizado --}}
                        <div style="margin-bottom: 8px;">
                            {{-- Isotipo: círculo con letra A estilizada --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 16px auto;">
                                <tr>
                                    <td style="width: 52px; height: 52px; background-color: #22A86A; border-radius: 50%; text-align: center; vertical-align: middle; font-family: Arial, sans-serif; font-size: 26px; font-weight: 700; color: #0A3D26; line-height: 52px;">
                                        A
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="font-family: Arial, Helvetica, sans-serif; font-size: 28px; font-weight: 700; color: #6DDBA0; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 6px;">
                            AQUAVIVA
                        </div>
                        <div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 400; color: #8BA99A; letter-spacing: 2px; text-transform: uppercase;">
                            Gestión Ambiental &nbsp;·&nbsp; Plataforma Tecnológica
                        </div>

                        {{-- Línea divisoria decorativa --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 24px auto 0 auto; width: 60px;">
                            <tr>
                                <td style="height: 2px; background-color: #22A86A; border-radius: 1px;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- ===== CUERPO PRINCIPAL ===== --}}
                <tr>
                    <td style="background-color: #111C18; padding: 40px 40px 32px 40px;">

                        {{-- Saludo de bienvenida --}}
                        <p style="margin: 0 0 6px 0; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 400; color: #22A86A; letter-spacing: 2px; text-transform: uppercase;">
                            Empresa registrada exitosamente
                        </p>
                        <h1 style="margin: 0 0 16px 0; font-family: Arial, Helvetica, sans-serif; font-size: 24px; font-weight: 700; color: #E8F2EE; line-height: 1.3;">
                            Bienvenido, {{ $company->name }}
                        </h1>
                        <p style="margin: 0 0 32px 0; font-family: Arial, Helvetica, sans-serif; font-size: 15px; font-weight: 400; color: #8BA99A; line-height: 1.7;">
                            Tu empresa ha sido registrada exitosamente en <strong style="color: #6DDBA0;">Aquaviva Platform</strong>.
                            A continuación encuentras las credenciales de acceso para el administrador de tu organización.
                        </p>

                        {{-- ===== TARJETA DE CREDENCIALES ===== --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0B1512; border: 1px solid #1F3329; border-radius: 12px; margin-bottom: 32px;">
                            <tr>
                                <td style="padding: 28px 28px 8px 28px;">

                                    {{-- Encabezado tarjeta --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                        <tr>
                                            <td>
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: 700; color: #22A86A; letter-spacing: 2.5px; text-transform: uppercase;">
                                                    &#9632; &nbsp;CREDENCIALES DE ACCESO
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Separador --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                        <tr>
                                            <td style="height: 1px; background-color: #1F3329;"></td>
                                        </tr>
                                    </table>

                                    {{-- Fila: Empresa --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 16px;">
                                        <tr>
                                            <td width="130" style="vertical-align: top; padding-bottom: 4px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: 400; color: #4D6B5C; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    Empresa
                                                </span>
                                            </td>
                                            <td style="vertical-align: top; padding-bottom: 4px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: 600; color: #E8F2EE;">
                                                    {{ $company->name }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Fila: NIT --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                        <tr>
                                            <td width="130" style="vertical-align: top; padding-bottom: 4px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: 400; color: #4D6B5C; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    NIT
                                                </span>
                                            </td>
                                            <td style="vertical-align: top; padding-bottom: 4px;">
                                                <span style="font-family: 'Courier New', Courier, monospace; font-size: 14px; font-weight: 600; color: #E8F2EE; letter-spacing: 1px;">
                                                    {{ $company->nit }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Separador punteado --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                        <tr>
                                            <td style="height: 1px; background-color: #1F3329;"></td>
                                        </tr>
                                    </table>

                                    {{-- Fila: Correo (protegido con spans) --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 16px;">
                                        <tr>
                                            <td width="130" style="vertical-align: top; padding-top: 2px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: 400; color: #4D6B5C; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    Correo
                                                </span>
                                            </td>
                                            <td style="vertical-align: top;">
                                                {{-- Técnica anti-scraping: email dividido en spans individuales --}}
                                                <div style="font-family: 'Courier New', Courier, monospace; font-size: 14px; color: #6DDBA0; letter-spacing: 0.5px; line-height: 1.4;" aria-label="{{ $adminUser->email }}">
                                                    @php $emailChars = str_split($adminUser->email); @endphp
                                                    @foreach($emailChars as $char)
                                                        <span style="display: inline-block;">{{ $char }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Fila: Contraseña (protegida con spans) --}}
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                        <tr>
                                            <td width="130" style="vertical-align: top; padding-top: 2px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: 400; color: #4D6B5C; letter-spacing: 0.5px; text-transform: uppercase;">
                                                    Contraseña
                                                </span>
                                            </td>
                                            <td style="vertical-align: top;">
                                                {{-- Técnica anti-scraping: contraseña dividida en spans individuales --}}
                                                <div style="font-family: 'Courier New', Courier, monospace; letter-spacing: 3px; color: #22A86A; font-size: 18px; font-weight: 700; line-height: 1.4;" aria-label="Contraseña provisional">
                                                    @php $chars = str_split($temporaryPassword); @endphp
                                                    @foreach($chars as $char)
                                                        <span style="display: inline-block;">{{ $char }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            {{-- Aviso de seguridad de contraseña dentro de la tarjeta --}}
                            <tr>
                                <td style="padding: 0 28px 24px 28px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="background-color: #0D5233; border-radius: 6px; padding: 10px 14px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 400; color: #6DDBA0; line-height: 1.5;">
                                                    &#9888;&nbsp; Por seguridad, cambia tu contraseña en el <strong>primer inicio de sesión</strong>.
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        {{-- ===== PRÓXIMOS PASOS ===== --}}
                        <p style="margin: 0 0 16px 0; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 700; color: #8BA99A; letter-spacing: 1.5px; text-transform: uppercase;">
                            Próximos pasos
                        </p>

                        {{-- Paso 1 --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 12px;">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-top: 1px;">
                                    <div style="width: 28px; height: 28px; background-color: #0A3D26; border: 1px solid #1F3329; border-radius: 50%; text-align: center; line-height: 28px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 700; color: #22A86A;">
                                        1
                                    </div>
                                </td>
                                <td style="vertical-align: top; padding-top: 4px; padding-left: 4px;">
                                    <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: 600; color: #E8F2EE; line-height: 1.5;">
                                        Ingresa a la plataforma
                                    </p>
                                    <p style="margin: 2px 0 0 0; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 400; color: #8BA99A; line-height: 1.5;">
                                        Usa las credenciales de acceso que encontrarás arriba.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Paso 2 --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 12px;">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-top: 1px;">
                                    <div style="width: 28px; height: 28px; background-color: #0A3D26; border: 1px solid #1F3329; border-radius: 50%; text-align: center; line-height: 28px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 700; color: #22A86A;">
                                        2
                                    </div>
                                </td>
                                <td style="vertical-align: top; padding-top: 4px; padding-left: 4px;">
                                    <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: 600; color: #E8F2EE; line-height: 1.5;">
                                        Actualiza tu contraseña
                                    </p>
                                    <p style="margin: 2px 0 0 0; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 400; color: #8BA99A; line-height: 1.5;">
                                        Ve a <strong style="color: #6DDBA0;">Configuración → Perfil</strong> y establece una contraseña segura.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Paso 3 --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 32px;">
                            <tr>
                                <td width="36" style="vertical-align: top; padding-top: 1px;">
                                    <div style="width: 28px; height: 28px; background-color: #0A3D26; border: 1px solid #1F3329; border-radius: 50%; text-align: center; line-height: 28px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 700; color: #22A86A;">
                                        3
                                    </div>
                                </td>
                                <td style="vertical-align: top; padding-top: 4px; padding-left: 4px;">
                                    <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: 600; color: #E8F2EE; line-height: 1.5;">
                                        Agrega tu equipo
                                    </p>
                                    <p style="margin: 2px 0 0 0; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 400; color: #8BA99A; line-height: 1.5;">
                                        Invita a los integrantes de tu organización desde <strong style="color: #6DDBA0;">Administración → Usuarios</strong>.
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- ===== AVISO DE SEGURIDAD ===== --}}
                <tr>
                    <td style="background-color: #1A2E24; padding: 20px 40px; border-top: 1px solid #1F3329;">
                        <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 400; color: #4D6B5C; line-height: 1.6; text-align: center;">
                            &#128274;&nbsp; Este correo contiene información confidencial. Si no eres el destinatario
                            previsto, por favor elimínalo y notifica a
                            <a href="mailto:soporte@aquaviva.com.co" style="color: #22A86A; text-decoration: none;">soporte@aquaviva.com.co</a>.
                        </p>
                    </td>
                </tr>

                {{-- ===== FOOTER ===== --}}
                <tr>
                    <td style="background-color: #0A3D26; border-radius: 0 0 12px 12px; padding: 24px 40px; text-align: center;">

                        <p style="margin: 0 0 4px 0; font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: 700; color: #6DDBA0; letter-spacing: 1px;">
                            AQUAVIVA SAS &nbsp;·&nbsp; Gestión Ambiental
                        </p>
                        <p style="margin: 0 0 12px 0; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 400; color: #8BA99A;">
                            <a href="mailto:soporte@aquaviva.com.co" style="color: #22A86A; text-decoration: none;">soporte@aquaviva.com.co</a>
                        </p>

                        {{-- Línea divisoria --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 12px auto; width: 40px;">
                            <tr>
                                <td style="height: 1px; background-color: #1F3329;"></td>
                            </tr>
                        </table>

                        <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: 400; color: #4D6B5C; line-height: 1.6;">
                            &copy; {{ date('Y') }} Aquaviva SAS. Todos los derechos reservados.<br>
                            Este es un correo automático, por favor no responder directamente.
                        </p>
                    </td>
                </tr>

            </table>
            {{-- Fin contenedor 600px --}}

        </td>
    </tr>
</table>

</body>
</html>
