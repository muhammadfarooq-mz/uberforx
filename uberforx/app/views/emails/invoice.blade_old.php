<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <?php $siteName = Config::get('mail.from.name'); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Your Trip Details</title>
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
                                    <a href="#" style="text-decoration:none;"><h3 style="font-size: 25px;font-family: 'Helvetica Neue', helvetica, arial, sans-serif;font-weight: bold;color: #6B6B6B;margin: 0;"><?php echo $siteName; ?></h3></a>
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
<?php /* $currency = Keywords::where('id', 5)->first(); */ ?>
<!-- Fin en-tête -->

<table id="email-penrose-conteneur" width="660" align="center" style="border-right:1px solid #e2e8ea; border-bottom:1px solid #e2e8ea; border-left:1px solid #e2e8ea; background-color:#ffffff;" border="0" cellspacing="0" cellpadding="0">

    <!-- Début bloc "mise en avant" -->

    <tr>
        <td style="background-color:#2ecc71">
            <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="resp-full-td" valign="top" style="padding:20px; text-align:center;">
                        <span style="font-size:25px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100; color:#ffffff"><a href="#" style="color:#ffffff; outline:none; text-decoration:none;"><?php if ($email_data['emailType'] == 'user') { ?> 
                                    Thank for choosing <?php echo $siteName . ', ' . $email_data['name']; ?>
                                <?php } else { ?>
                                    Trip Details <?php } ?>
                            </a></span>
                    </td>
                </tr>					
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0" style="padding:20px;">
                <tr>
                    <?php if ($email_data['map']) { ?>	
                    <div id="trip-map" class="col-lg-4">
                        <img width="250" height="250" src="<?php echo $email_data['map']; ?>" alt="map location">
                    </div>
                <?php } ?>
                <td width="100%">

                    <!-- Début bloc info 1 -->

                    <table width="300" align="left" class="resp-full-table" style="margin:0 auto;float:none;background-color:#2ecc71; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%" class="resp-full-td" valign="top" style="text-align: justify; padding:20px;">
                                <a href="#" style="outline:none; text-decoration:none"><span style="font-size:25px; font-weight: bold; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;">Fare</span></a><br />
                                <hr align="left" style="width:100px; margin-left:0px; text-align:left; background-color:#ffffff; color:#ffffff; height: 2px; border: 0 none;" />
                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Base Fare</span><span style="float:right;"><?php echo /* $currency->keyword */Config::get('app.generic_keywords.Currency') . ' ' . $email_data['base_price']; ?></span></div>
                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Distance</span><span style="float:right;"><?php echo round($email_data['distance'], 2); ?> <?php echo $email_data['unit']; ?></span></div>
                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Time</span><span style="float:right;"><?php echo $email_data['time']; ?></span></div>
                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Discount</span><span style="float:right;"><?php echo $email_data['promo_discount']; ?></span></div>
                                <hr style="background-color:#ffffff; color:#ffffff; height: 1px; border: 0 none">

                                <div style="font-weight:bold;margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Sub Total</span><span style="float:right;"> <?php echo /* $currency->keyword */Config::get('app.generic_keywords.Currency') . ' ' . $email_data['total']; ?></span></div>
                            </td>
                        </tr>

                    </table>

                    <!-- Fin bloc info 1 -->

                                        <!-- 		<table width="20" align="left" class="resp-full-table" border="0" cellpadding="0" cellspacing="0" >
                                                                <tr>
                                                                        <td width="100%" height="20"></td>
                                                                </tr>
                                                        </table> -->

                    <!-- Début bloc info 2 -->

                                                        <!-- <table width="300" align="left" class="resp-full-table" style="background-color:#2ecc71; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                        <td width="100%" class="resp-full-td" valign="top" style="text-align: justify; padding:20px;">
                                                                                <a href="#" style="outline:none; text-decoration:none"><span style="font-size:25px; font-weight: bold; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;">Location</span></a><br />
                                                                                <hr align="left" style="width:100px; margin-left:0px; text-align:left; background-color:#ffffff; color:#ffffff; height: 2px; border: 0 none;" />
                                                                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Pickup Location</span><span style="float:right;">Madiwala</span></div>
                                                                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Drop Location</span><span style="float:right;">Marathahalli</span></div>
                                                                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Time of Travel</span><span style="float:right;">12.33</span></div>
                                                                                <hr style="background-color:#ffffff; color:#ffffff; height: 1px; border: 0 none">

                                                                                <div style="margin: 10px 0px;font-size:16px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#ffffff;"><span>Payment Mode</span><span style="float:right;">Card</span></div>
                                                                        </td>
                                                                </tr>
                                                
                                                        </table>
                    -->
                    <!-- Fin bloc info 2 -->

                </td>
    </tr>
</table>
</td>
</tr>

<!-- Fin bloc "mise en avant" -->
<!-- Début article 1 -->

<tr>
    <td style="border-bottom: 1px solid #e2e8ea">
        <table width="660" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0" style="padding:20px;">
            <tr>
                <td width="100%">

                    <table width="100%" align="right" class="resp-full-table" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%" class="resp-full-td" valign="top" style="text-align : justify;">
                                <div style="background:#2ECC71;padding: 10px;font-size:25px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-weight:100; color:#ffffff">TOTAL<span style="float:right;"><?php echo /* $currency->keyword */Config::get('app.generic_keywords.Currency') . ' ' . $email_data['total']; ?></span></div>
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

<table id="email-penrose-conteneur" width="600" align="center" style="padding:20px 0px;" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table width="600" class="resp-full-table" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" class="resp-full-td" style="text-align: center;"><span style="font-size:12px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#aeb2b3">Fares are inclusive of service tax and all other taxes applicable. Service tax and all applicable taxes are the responsibility of the transportation service provider..</span>
                        <hr align="left" style="margin-left:0px; text-align:left; background-color:#aeb2b3; color:#aeb2b3; height: 1px; border: 0 none;" />
                        <span style="font-size:12px; font-family:'Helvetica Neue', helvetica, arial, sans-serif; color:#aeb2b3">Fare does not include fees that may be charged by your bank. Please contact your bank directly for inquiries.</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Fin footer -->

</div>
</body>
</html>