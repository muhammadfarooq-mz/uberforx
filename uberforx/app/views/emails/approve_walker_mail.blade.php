<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Driver Approved</title>
        <style type="text/css" media="screen">

            .ExternalClass * {line-height: 100%}

            /* Début style responsive (via media queries) */

            @media only screen and (max-width: 480px) {
                *[id=email-penrose-conteneur] {width: 100% !important;}
                table[class=resp-full-table] {width: 100%!important; clear: both;}
                td[class=resp-full-td] {width: 100%!important; clear: both;}
                img[class="email-penrose-img-header"] {width:100% !important; max-width: 340px !important;}
            }

            /* Fin style responsive */

        </style>

    </head>
    <body style="background-color:#ecf0f1">
        <div align="center" style="background-color:#ecf0f1;">

            <!-- Début en-tête -->

            <table id="email-penrose-conteneur" width="660" align="center" style="padding:20px 0px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" style="text-align:left;">
                                    <a href="{{ web_url() }}" style="text-decoration:none;"><h3 style="font-size: 25px;font-family: 'Helvetica Neue', helvetica, arial, sans-serif;font-weight: bold;color: #6B6B6B;margin: 0;"><?php /* echo "App Name"; */ echo ucwords(Config::get('app.website_title')); ?></h3></a>
                                </td>
                                <td width="50%" style="text-align:right;">
                                    <table align="right" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                        <h5 style="font-size: 20px;font-family: 'Helvetica Neue', helvetica, arial, sans-serif;font-weight: bold;color: #6B6B6B;margin: 0;"><?php echo date("d-m-Y"); ?></h5>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Fin en-tête -->

<table id="email-penrose-conteneur" width="660" align="center" style="border-right:1px solid #e2e8ea; border-bottom:1px solid #e2e8ea; border-left:1px solid #e2e8ea; background-color:#ffffff;" border="0" cellspacing="0" cellpadding="0">

    <!-- Début bloc "mise en avant" -->

    <tr>
        <td style="background-color:#ff9d3d">
            <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="resp-full-td" valign="top" style="padding:20px; text-align:center;">
                        <span style="font-size:25px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100; color:#ffffff"><a href="{{ web_url() }}" style="color:#ffffff; outline:none; text-decoration:none;">Welcome {{ $mail_body['walker_name'] }} To <?php /* echo "App Name"; */ echo ucwords(Config::get('app.website_title')); ?></a></span>
                    </td>
                </tr>
                <!-- <tr>
                        <td width="100%" class="resp-full-td" valign="top" style="padding: 0px 20px 20px 20px;">
                                <table align="center" border="0" cellspacing="0" cellpadding="0" style="margin:auto; padding:auto;">
                                        <tr>
                                                <td style="background-color:#ffffff; border-radius:3px; padding: 10px 40px;">
                                                        <a style="font-family:'Helvetica Neue', helvetica, arial, sans-serif; text-align: center; text-decoration: none; display:block; color:#ff9d3d; font-weight : 200; font-size: 25px;" href="#">J'en profite</a>
                                                </td>
                                        </tr>
                                </table>
                        </td>
                </tr> -->

            </table>
        </td>
    </tr>





    <!-- Début article 1 -->

    <tr>
        <td style="border-bottom: 1px solid #e2e8ea">
            <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0" style="padding:20px;">
                <tr>
                    <td width="100%">

                        <table width="100%" align="right" class="resp-full-table" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="100%" class="resp-full-td" valign="top" style="text-align : justify;">

                                    <div style="padding: 10px;font-size:12px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100; color:#545454;text-align:center;">
                                        You are successfully approved as a driver.

                                        You are ready to serve <strong style="font-weight: bold;"><?php /* echo "App Name"; */echo ucwords(Config::get('app.website_title')); ?></strong> clients.
                                    </div>
                                    <br>
                                    <!--<div style="text-align:center"><a href="#" style="padding: 10px;font-size:18px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100;background-color: #ff9d3d;color:#fff;text-align:center;  text-decoration: none;">Login</a></div>


                                    <br>-->
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" class="resp-full-td" valign="top" style="text-align : justify;">

                                    <div style="padding: 10px;font-size:12px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100; color:#545454;text-align:center;">
                                        For any query or consolation {{ $mail_body['admin_eamil'] }}
                                    </div>
                                    <!--<div style="text-align:center"><a href="#" style="padding: 10px;font-size:18px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100;background-color: #ff9d3d;color:#fff;text-align:center;  text-decoration: none;">Login</a></div>


                                    <br>-->
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Fin article 1 -->

</table>

<!-- Début footer -->


<!-- Fin footer -->

</div>
</body>
</html>