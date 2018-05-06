<?php

use backend\models\enums\CustomerTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\PricingTypes;
use backend\models\enums\SourceTypes;
use common\models\InquiryPackage;
use yii\helpers\Url;

$roomtypes = '';$ch_ages ='';$child_ages='';
if (isset($model->inquiryRoomTypes)) {
    foreach ($model->inquiryRoomTypes as $room) {
        $roomtypes .= $room->roomType->type;
        $roomtypes .= ',';
    }
    $roomtypes = rtrim($roomtypes, ',');
}
if($model->children_count > 0)
{
    if(isset($model->inquiryChildAges))
    {
        $ch_ages ='';

        foreach($model->inquiryChildAges as $age)
        {
            $ch_ages .= $age->age.' years,';
            //echo $age->age.' years,';

        }

        $child_ages = rtrim($ch_ages, ",");
    }
}

$link = Yii::$app->urlManager->createAbsoluteUrl(['inquiry/view', 'id' => $model->id]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Inquiry</title>
</head>
<body>
<table border="1" cellspacing="0" cellpadding="0" style="border:solid 1px #000000;border-collapse:collapse; font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;">
    <tbody>
    <tr style="">
        <td width="100%" colspan="3" valign="top"
            style="background:#329dd1;color: #ffffff;">
            <p align="center" style="text-align:center"><u><span style="font-size:20px"><b>Travel Agency</b><u></u><u></u></span></u>
            </p>
        </td>
    </tr>
    <tr style="border:solid 1px #000000;border-bottom:solid 1px #e5e5e5;">
        <td style="background:#e5e5e5;padding-left:5px;">
            <p style="font-size:15px"><b>Destination: <?=$model->destination?></b></p>
        </td>
        <td style="background:#e5e5e5;border-left:solid 1px #e5e5e5;border-right:solid 1px #e5e5e5;"></td>
        <td style="background:#e5e5e5;padding-right:5px;">
            <p style="font-size:15px"><b>Inquiry Number: <?= 'KR- '.$model->inquiry_id?></b></p>
        </td>
    </tr>
    <tr style="border:solid 1px #000000;border-top:solid 1px #e5e5e5;">
        <td style="background:#e5e5e5;padding-left:5px;">
            <p style="font-size:15px"><b>Source: <?=SourceTypes::$headers[$model->source]?></b></p>
        </td>
        <td style="background:#e5e5e5;border-left:solid 1px #e5e5e5;border-right:solid 1px #e5e5e5;"></td>
        <td style="background:#e5e5e5;padding-right:5px;">
            <p style="font-size:15px"><b>Customer Type: <?=CustomerTypes::$headers[$model->customer_type]?></b></p>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="3" valign="top"
            style="background:#fffffff; padding-left:5px;">
            <p><span style="font-size:13px">Dear <?=$username?>,</span></p>
            <p><span style="font-size:13px">You have been assigned below inquiry:</span></p>
        </td>
    </tr>
    <tr>
        <td width="200" valign="top"
            style="background:#e5e5e5;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Passenger Name</span></p>
        </td>
        <td width="200" valign="top"
            style="background:#e5e5e5;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Passenger Email</span>
            </p></td>
        <td width="200"  valign="top"
            style="background:#e5e5e5;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Passenger Mobile</span>
            </p></td>
    </tr>
    <tr>
        <td width="200" valign="top"
            style="background:#ffffff;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->name?></span></p>
        </td>
        <td width="200" valign="top"
            style="background:#ffffff;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->email?></span>
            </p></td>
        <td width="200" valign="top"
            style="background:#ffffff;">
            <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->mobile?></span>
            </p></td>
    </tr>
    <tr style="height: 30px;">
        <td width="100%" valign="top" colspan="3"
            style="background:#ffffff"></td>
    </tr>
    <tr>
        <td valign="top" colspan ="3" width="100%">
            <table border="1" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse; font-family:Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;">
                <tbody>
                <tr style="border-top: solid 1px #e5e5e5;">
                    <td valign="top"
                        style="background:#e5e5e5;width:12%;border-left:solid 1px #e5e5e5;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">From</span></p>
                    </td>
                    <td  valign="top"
                         style="background:#e5e5e5;width:12%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">To</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:9%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Nights</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:18%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Leaving From</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:10%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Adults</span></p>
                    </td>
                    <td  valign="top"
                         style="background:#e5e5e5;width:10%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Child</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:15%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Child Age</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:16%;border-top:solid 1px #e5e5e5;border-right:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Room Type</span>
                        </p></td>
                </tr>
                <tr>
                    <td valign="top"
                        style="background:#ffffff;;border-left:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=date("d/m/y",$model->from_date)?></span></p>
                    </td>
                    <td  valign="top"
                         style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=date("d/m/y",$model->return_date)?></span>
                        </p></td>
                    <td valign="top"
                        style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->no_of_days?></span>
                        </p></td>
                    <td  valign="top"
                         style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->leaving_from?></span>
                        </p></td>
                    <td valign="top"
                        style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->adult_count?></span></p>
                    </td>
                    <td  valign="top"
                         style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->children_count?></span>
                        </p></td>
                    <td valign="top"
                        style="background:#ffffff;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->children_count>0 ? $child_ages : "-"?></span>
                        </p></td>
                    <td  valign="top"
                         style="background:#ffffff;border-right:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$roomtypes?></span>
                        </p></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr style="height: 30px;">
        <td width="100%" valign="top" colspan="3"
            style="background:#ffffff;border-top:solid 1px #e5e5e5;"></td>
    </tr>
    <tr>
        <td valign="top" colspan ="3" width="100%">
            <table border="1" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse; font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;;">
                <tbody>
                <tr style="border-top: solid 1px #e5e5e5;">
                    <td valign="top"
                        style="background:#e5e5e5;width:20%;border-top:solid 1px #e5e5e5;border-left:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Quotation Manager</span></p>
                    </td>
                    <td  valign="top"
                         style="background:#e5e5e5;width:20%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Quotation Staff</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:20%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Followup Manager</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:20%;border-top:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Followup Staff</span>
                        </p></td>
                    <td valign="top"
                        style="background:#e5e5e5;width:20%;border-top:solid 1px #e5e5e5;border-right:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:bold">Inquiry Head</span>
                        </p></td>
                </tr>
                <tr>
                    <td valign="top"
                        style="background:#ffffff;border-bottom:solid 1px #e5e5e5;border-left:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->quotation_manager!="" ? $model->quotationManager->username: "-"?></span></p>
                    </td>
                    <td  valign="top"
                         style="background:#ffffff;border-bottom:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->quotation_staff!="" ? $model->quotationStaff->username: "-"?></span>
                        </p></td>
                    <td valign="top"
                        style="background:#ffffff;border-bottom:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->follow_up_head!="" ? $model->followUpHead->username: "-"?></span>
                        </p></td>
                    <td  valign="top"
                         style="background:#ffffff;border-bottom:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->follow_up_staff!="" ? $model->followUpStaff->username: "-"?></span>
                        </p></td>
                    <td  valign="top"
                         style="background:#ffffff;border-bottom:solid 1px #e5e5e5;border-right:solid 1px #e5e5e5;">
                        <p style="text-align:center"><span style="font-size:14px;font-weight:normal"><?=$model->inquiry_head!="" ? $model->inquiryHead->username: "-"?></span>
                        </p></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr style="height: 30px;">
        <td width="100%" valign="top" colspan="3"
            style="background:#ffffff;"></td>
    </tr>
    <tr>
        <td width="100%" colspan="3" valign="top"
            style="background:#e5e5e5;">
            <p align="center" style="text-align:center"><span style="font-size:14px;font-weight:bold">Inclusions</span>
            </p></td>
    </tr>
    <tr>
        <td colspan="3" valign="top" style="padding-left:5px;">
            <p><span style="font-size:14px;font-weight:normal;"><?=$model->inquiry_details?></span>
            </p>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
