<?php
// src/templates/email_base.php

function renderEmailTemplate($userName, $messageBody, $actionLink = null, $actionText = 'Acessar Sistema')
{
    // Branding Colors
    $primaryColor = '#10b981'; // Emerald 500
    $bgColor = '#f8fafc'; // Slate 50

    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificação - Organizador Financeiro</title>
</head>
<body style="margin: 0; padding: 0; background-color: $bgColor; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: $bgColor; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Wrapper -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">Organizador Financeiro</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #334155; line-height: 1.6;">
                                Olá, <strong>$userName</strong>!
                            </p>
                            
                            <div style="font-size: 16px; color: #334155; line-height: 1.6; margin-bottom: 30px;">
                                $messageBody
                            </div>
                            
                            <!-- Action Button -->
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="$actionLink" style="height:50px;v-text-anchor:middle;width:200px;" arcsize="10%" stroke="f" fillcolor="$primaryColor">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                                <a href="$actionLink" style="background-color: $primaryColor; border-radius: 8px; color: #ffffff; display: inline-block; font-family: sans-serif; font-size: 16px; font-weight: bold; line-height: 50px; text-align: center; text-decoration: none; width: 100%; max-width: 240px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);">
                                    $actionText
                                </a>
                            <!--[if mso]>
                                </center>
                            </v:roundrect>
                            <![endif]-->
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f1f5f9; padding: 20px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #64748b;">
                                Este é um e-mail automático. Por favor, não responda.<br>
                                &copy; 2024 Organizador Financeiro.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}
?>