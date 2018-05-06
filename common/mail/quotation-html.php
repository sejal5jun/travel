<?php

use backend\models\enums\DirectoryTypes;
use backend\models\enums\PricingTypes;
use common\models\InquiryPackage;
use yii\helpers\Url;

$roomtypes = '';

if (isset($model->inquiryPackageRoomTypes)) {
    foreach ($model->inquiryPackageRoomTypes as $room) {
        $roomtypes .= $room->roomType->type;
        $roomtypes .= ',';
    }
    $roomtypes = rtrim($roomtypes, ',');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>Quotation</title>
</head>
<body>

<table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse; font-family:Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;">
<tbody>
<tr style="">
    <td width="100%" colspan="3" valign="top"
        style="background:#fffffff;padding:15px;border-top: solid 1px #ffffff;border-left: solid 1px #ffffff;border-right: solid 1px #ffffff;">
        <p><span style="font-size:16px">Dear  <?= $model->passenger_name ?>,</b></span></p>

        <p><span style="font-size:16px"><?= $customize_text?>
                   </span></p>
    </td>
</tr>
<tr style="">
    <td width="100%" colspan="3" valign="top"
        style="background:#329dd1;height: 25px;">

    </td>
</tr>
<?php if(isset($model->package_id)){ ?>
    <tr style="">
        <td colspan="3" valign="top"
            style=" ">
            <p align="center" style="text-align:center"><span
                    style="font-size:18px;"><?= strtoupper($model->package_name) ?></span><br/>
                <span style="font-size:15px"> <?php if ($model->no_of_days_nights != '') {
                        $nights = $model->no_of_days_nights;
                    } else {
                        if (isset($model->quotationItineraries))
                            $nights = $model->quotationItineraries[0]->no_of_itineraries . ' Nights';
                        else
                            $nights = 0;
                    } ?>
                    <?php echo $nights; ?> Nights / <?= $nights + 1 ?> Days</span><br/>

                <span style="font-size:15px"><?= $model->itinerary_name ?></span>
            </p>
    </tr>
<?php }  else{?>
    <tr>
        <td colspan="3" valign="top"
            style="border-top:none; ">
            <p align="center" style="text-align:center"><span
                    style="font-size:18px;"><?= strtoupper($model->destination) ?></span><br/>
                <span style="font-size:15px"> <?php
                    $nights = 0;
                    $nights = $model->no_of_nights;
                    ?>
                    <?php echo $nights; ?> Nights / <?= $nights + 1 ?> Days</span><br/>
            </p>
    </tr>
<?php } ?>
<tr>
    <td width="100%" colspan="3" valign="top"
        style="background:#329dd1;color: #ffffff;">
        <p align="center" style="text-align:center"><span style="font-size:15px;"><b>Travelling Details</b></span>
        </p>
    </td>
</tr>


<tr style="">
    <td width="50%" valign="top"
        style="border-top:none;background:#ffffff; ">
        <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
            <tr>
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span style="font-size:15px;font-weight:normal">Check-in:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"><?= date("d-m-y", $model->from_date) ?></span>

                    </p>
                </td>
            </tr>
            <tr style="">
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span style="font-size:15px;font-weight:normal">No of Nights:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"><?= $model->no_of_nights ?></span>

                    </p>
                </td>
            </tr>
            <tr>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal">Adults:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"><?= $model->adult_count ?></span>

                    </p>
                </td>
            </tr>
        </table>
    </td>
    <td width="50%" valign="top"
        style="background:#ffffff; ">
        <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
            <tr style="">
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span style="font-size:15px;font-weight:normal">Check-out:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"><?= date("d-m-y", $model->return_date) ?></span>

                    </p>
                </td>
            </tr>
            <tr style="">
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span style="font-size:15px;font-weight:normal">Rooms:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"><?= $model->room_count ?></span>

                    </p>
                </td>
            </tr>
            <tr style="">
                <td valign="top"
                    style="border:none; padding-left: 20px ">
                    <p style="text-align:left"><span style="font-size:15px;font-weight:normal">Children:</span>

                    </p>
                </td>
                <td valign="top"
                    style="border:none;padding-left: 20px  ">
                    <p style="text-align:left"><span
                            style="font-size:15px;font-weight:normal"> <?= isset($model->children_count) ? $model->children_count : 0 ?>

                            <?php if (isset($model->inquiryPackageChildAges)) {
                                $child_age='';
                                if($model->children_count!='' || $model->children_count!=0) {
                                    foreach ($model->inquiryPackageChildAges as $age) {
                                        if($age->age!='')
                                            $child_age .= $age->age . ' years,';
                                    }
                                    $child_age =  rtrim($child_age, ",");
                                    if($child_age!="")
                                        echo "/" . $child_age;
                                }
                            }?></span>

                    </p>
                </td>
            </tr>
        </table>
    </td>

</tr>
<?php if ($model->is_itinerary == InquiryPackage::WITH_ITINERARY) {
    if (count($model->quotationItineraries) > 0) {
        $count = $model->no_of_days_nights ?>
        <?php for ($i = 0; $i < $count+1; $i++) {
            $it = $model->quotationItineraries[$i];?>
            <tr style="height:25px;">
                <td width="100%" valign="top" colspan="2"
                    style="border-top:none;background:#ffffff; "></td>
            </tr>

            <tr style="">
                <td width="100%" colspan="3" valign="top"
                    style="background:#ececec;padding-left:5px;">
                    <p align="center" style="text-align:left"><span style="font-size:15px;font-weight:bold"><?= $it->title ?></span>
                    </p></td>
            </tr>

            <tr >
                <td colspan="2">
                    <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse" >
                        <tr>
                            <td  valign="top"
                                 style="background:#ffffff;padding:5px;">
                                <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                    <tr style="">
                                        <td valign="top"
                                            style="">
                                            <?php if($it->media_id != ''){ ?>
                                            <img
                                                src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($model->package->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?>"
                                                height=100px width=100px/>
                                            <?php } ?>
                                        </td>

                                    </tr>

                                </table>
                            </td>
                            <td  valign="top"
                                 style="background:#ffffff; ">
                                <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                    <tr style="">
                                        <td valign="top">
                                            <p style="text-align:left"><span
                                                    style="font-size:15px;font-weight:normal; font-family:Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;"><?= $it->description ?></span>

                                            </p>
                                        </td>

                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        <?php } ?>
    <?php } ?>
<?php } ?>
<?php if($model->package_id!='' && $model->quotationPricings[0]->price != ""){?>

    <tr style="">
        <td width="100%" colspan="2" valign="top"
            style="border-top:none;background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Pricing Details</span>
            </p></td>
    </tr>
    <tr style="">
        <td colspan="2" valign="top"
            style="padding-left:5px;">

            <?php $count = count($model->quotationPricings);
            for($i=0;$i<$count;$i++){
                $pm = $model->quotationPricings[$i];?>
                <p><span style="font-size:15px;font-weight:normal"><?= $pm->type0->type?>&nbsp; : <?= $pm->currency->name?><?= ' ' .$pm->price?></span>
                </p>
            <?php } ?>
            <p><span style="font-size:15px;font-weight:normal"><?=$model->pricing_details?></span>
            </p>

            <!--<p>Government Service Tax (3.5%) will be applicable on the above package.</p>-->
        </td>
    </tr>
<?php } ?>
<?php if ($model->terms_and_conditions!='') {?>

    <tr style="">
        <td width="100%" colspan="2" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Terms and Conditions</span>
            </p></td>
    </tr>
    <tr style="">
        <td colspan="2" valign="top"
            style="padding-left:5px; ">
            <p><span style="font-size:15px;font-weight:normal"><?=$model->terms_and_conditions?></span>
            </p>
        </td>
    </tr>
<?php } ?>
<?php if ($model->package_include!='') {?>

    <tr style="">
        <td width="100%" colspan="2" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Package Includes</span>
            </p></td>
    </tr>
    <tr style="">
        <td colspan="2" valign="top"
            style="padding-left:5px; ">
            <p><span style="font-size:15px;font-weight:normal"><?=$model->package_include?></span>
            </p>
        </td>
    </tr>
<?php } ?>
<?php if ($model->package_exclude!='') {?>

    <tr style="">
        <td width="100%" colspan="2" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Package Excludes</span>
            </p></td>
    </tr>
    <tr style="">
        <td colspan="2" valign="top"
            style="padding-left:5px; ">
            <p><span style="font-size:15px;font-weight:normal"><?=$model->package_exclude?></span>
            </p>
        </td>
    </tr>
<?php } ?>
<?php if ($model->other_info!='') {?>

    <tr style=>
        <td width="100%" colspan="2" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Other Information</span>
            </p></td>
    </tr>
    <tr >
        <td colspan="2" valign="top"
            style="padding-left:5px; ">
            <p><span style="font-size:15px;font-weight:normal"><?=$model->other_info?></span>
            </p>
        </td>
    </tr>
<?php } ?>
<?php if ($model->package_id == '') { ?>
    <tr style="height:25px;">
        <td width="100%" valign="top" colspan="2"
            style="background:#ffffff; "></td>
    </tr>
    <tr style="">
        <td width="100%" colspan="2" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><span style="font-size:15px;font-weight:bold">Hotel Details</span>
            </p></td>
    </tr>
    <tr style="">
        <td colspan="2" valign="top"
            style="padding-left:5px;">
            <p><span style="font-size:15px;font-weight:normal"><?=$model->hotel_details?></span>
            </p>
        </td>
    </tr>
<?php }?>
<tr style="">
    <td width="100%" colspan="3" valign="top"
        style="background:#fffffff;padding-top:15px;padding-left:5px;border-left: solid 1px #ffffff;border-bottom: solid 1px #ffffff;border-right: solid 1px #ffffff;">
        <p><span><?=$signature?></span></p>
    </td>
</tr>
</tbody>
</table>
</body>
</html>