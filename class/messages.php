<?php
    function m($messageId){
        $w=array();
        $w[1]     = 'e|This category can\'t make a child';
        $w[4]     = 'e|@1@';
        $w[5]     = 'i|@1@';
        $w[6]     = 's|@1@.';
        $w[7]     = 'i|Agent quota fill up.';
        $w[8]     = 'e|Password require at least 8 character with upercase, lowercase, number and special character.';
        $w[2]     = 'e|Invalid @1@ details request.';
        $w[3]     = 's|Sale quotation send successfully.';
        $w[29]    = 's|New @1@ add successfully.';
        $w[30]    = 's|@1@ update successfully.';
        $w[31]    = 'e|@1@ field is required';
        
        $w[34]    = 'e|Invalid @1@ view request.';
        $w[36]    = 'e|@1@ field is reqired.';
        $w[37]    = 'e|Invalid @1@ edit request.';
        
        $w[45]    = 'e|Invalid username or password.';
        $w[46]    = 'i|Some problem to your login. Please try again or contact your administartor.';
        $w[47]    = 'e|Invalid login.';
        $w[48]    = 'e|Your session has been expired.';
        
        $w[52]    = 'i|You are not authorize to access @1@.';
        
        $w[54]    = 'e|Password and confirm password not match.';
        
        $w[59]    = 'e|You are not authorize to edit @1@.';
        
        $w[63]    = 'e|Invalid @1@.';
        
        $w[82]    = 'e|You are not authorize to add @1@.';
        
        $w[108]   = 'e|Please select minimum one product for sale quotation.';
        $w[109]   = 'e|Grand Total amount cannot be zero.';
        
        $w[127]   = 'e|@1@ not available.';
        
        $w[147]   = 'e|Inactive user status contact your administrator.';
        $w[148]   = 'e|Inactive user group status contact your administrator.';
        
        
        
        $w[9]     = 'e|Invalid manufacturer edit request.';
        $w[10]    = 's|Manufacture update succcessfully.';
        $w[11]    = 'e|Invalid brand delete request.';
        $w[12]    = 'e|Company name field is required.';
        $w[13]    = 'e|Please select a valid country.';
        $w[14]    = 'e|City field is required.';
        $w[15]    = 'e|Contact No field is required.';
        $w[16]    = 'e|Contact Name field is required.';
        $w[17]    = 'e|Addres field is required.';
        $w[18]    = 's|New supplier add successfully.';
        $w[19]    = 'e|Invalid supplier edit request.';
        $w[20]    = 's|Supplier info update successfully.';
        $w[21]    = 'e|Invalid setup key request.';
        $w[22]    = 'e|Invalid setup value edit request.';
        $w[23]    = 'e|Code field is required.';
        $w[24]    = 'e|Value field is required.';
        $w[25]    = 's|Setup info update successfully.';
        $w[26]    = 's|Setup info add successfully.';
        $w[27]    = 'i|This code already user for this caption.';
        $w[28]    = 'i|This value already user for this caption.';
        $w[64]    = 'e|Referrance code required for @1@';
        $w[32]    = 'e|Brand field is required.';
        $w[33]    = 'e|Category field is required.';
        
        $w[35]    = 's|New product insert successfully.';
        $w[38]    = 's|Purchase successfully added.';
        $w[39]    = 'e|Invoice no field is required.';
        $w[40]    = 'e|Please select a valid supplier.';
        $w[41]    = 'e|Please select a valid role.';
        $w[42]    = 'e|Invalid payable amount.';
        $w[43]    = 's|Purchase invoice post successfully.';
        $w[44]    = 'e|May be a product multiple added.';
        $w[49]    = 's|You are successfully logout.';
//        $w[50]    = 'e|Invalid parent.';
        $w[51]    = 'e|Please select a valid parent';
        //52 check after 30
        $w[53]    = 'e|Invalid user for.';
//        $w[55]    = 'e|Username not available.';
        $w[56]    = 'e|Invalid product details request.';
        $w[57]    = 'e|Invalid product price details request.';
        $w[58]    = 'e|Invalid price.';
        $w[60]    = 's|Sale price update successfully.';
        $w[61]    = 'e|Please upload .png file.';
        $w[62]    = 'e|File size maximum 300KB';
        $w[65]    = 'e|Please select a valid customer.';
        $w[66]    = 'e|Some problem there. Please try again later.';
        $w[67]    = "e|Charge cannot be greater than amount.";
        $w[68]    = 's|New deposit insert successfully.';
        $w[69]    = 'e|[ <i>@1@</i> ] multiple in list.';
        $w[70]    = 'e|[ <i>@1@</i> ] stock quantity not enough. @2@ available.. Request @3@';
        $w[71]    = 'e|[ <i>@1@</i> ] free stock quantity not enough. @2@ available. Request @3@';
        $w[72]    = 'e|[ <i>@1@</i> ] sale price not set.';
        $w[73]    = 'e|Invalid comission for <i>@1@</i>.';
        $w[74]    = 'e|[ <i>@1@</i> ] total quantity cannot be ziro.';
        $w[75]    = 'e|Please select minimum one product for sale.';
        $w[76]    = 'e|Net sale amount cannot be zero.';
        $w[77]    = 'e|Due amount cannot be less then ziro.';
        $w[78]    = 'e|Customer not enough ballance.';
        $w[79]    = 'e|Invoice number already used.';
        $w[80]    = 's|Sale invoice post successfully.';
        $w[81]    = 'e|You are not authorize to set this type role.';
        $w[83]    = 'e|Invalid product for this invoice';
        $w[84]    = 's|Sale return entry successfully.';
        $w[85]    = 'e|Invalid sale details request.';
        $w[86]    = 'i|Any product not set for return.';
        $w[87]    = 'e|Invalid purchase details request.';
        $w[88]    = 's|Purchase return entry successfully.';
        $w[89]    = 'e|Maximum pay amount @1@.';
        $w[90]    = 'e|Not accept pay amount zero.';
        $w[91]    = 'e|Invalid sale payment request';
        $w[92]    = "e|It's already paid.";
        $w[93]    = 's|New payment recive successfully.';
        $w[94]    = 'e|Customer not have enouth balance.';
        $w[95]    = 's|Deposit return successfully.';
        $w[96]    = 'e|@1@ is currently inactive.';
        $w[97]    = 'e|Please select some valide product for transfer.';
        $w[98]    = 'e|Your cannot deliver any product to same branch.';
        $w[99]    = 'e|Invalid quantity for [<i>@1@</i>]';
        $w[101]   = 's|Your Transfer has is set in draft and waiting for authorized persosnals approvel.';
        $w[102]   = 'e|Please select minimum one product for Transfer.';
        $w[103]   = 's|Product transfer successfully.';
        $w[104]   = 's|Purchase Edit Done Successfully.';
        $w[105]   = 's|Purchase invoice save to draft.';
        $w[106]   = 's|Sale invoice save to draft.';
        $w[107]   = 'e|Quotation ID number already used.';
        $w[110]   = 's|Sale quotation saved.';
        $w[228]   = 's|Optimation Bill saved.';
        $w[111]   = 'e|@1@ cannot be empty.';
        $w[112]   = 'e|Quotation ID field is required.';
        $w[113]   = 'e|Part no already used.';
        $w[114]   = 's|Stock to reserve transfer successfully.';
        $w[115]   = 's|New adjut note entry successfully.';
        $w[116]   = 'e|Allready back this reserve';
        $w[117]   = 'e|Invalid quantity. @1@ available, @2@ request.';
        $w[118]   = 's|Product reserve to stock transfer successfully.';
        $w[119]   = 's|Delivery note update successfully.';
        $w[120]   = 'e|[ <i>@1@</i> ] delivery remining @2@. Request @3@.';
        $w[121]   = 's|@1@ Edit Done Successfully.';
        $w[122]   = 'e|Allready partially draft';
        $w[123]   = 'e|Allready submited.';
        $w[124]   = 'e|Ledger list cannot be empty.';
        $w[125]   = 'e|Total Debit and Credit balance must be equal for each branch.';
        $w[126]   = 's|New Voucher @1@ successfully.';
        $w[128]   = 'e|@1@ Chart of accounts not set';
        $w[129]   = 'e|This branch not ready for any transection. Please contact Administrator';
        $w[130]   = 'e|Paid amount cannot be greater then invoice amount';
        $w[131]   = 'i|Your request URL currently unavailable';
        $w[132]   = 'e|You are not authorize to any sale for @1@ branch.';
        $w[133]   = 'e|You are not authorize to cash sale for @1@ branch.';
        $w[134]   = 'e|Invalid From branch';
        $w[135]   = 'e|Invalid To branch';
        $w[136]   = 'e|Invalid From Ledger';
        $w[137]   = 'e|Invalid To Ledger';
        $w[138]   = 'e|Please select different branch for transfer.';
        $w[139]   = 'i|Old Cost and new cost are same. So there are no need to update.';
        $w[140]   = 'i|[<i>@1@</i>] Unit cost not set. Please set unit cost or contact your superior officer.';
        $w[141]   = 'i|আপনি একজন সুপার এডমিন হিসেবে লগইন করেছেন। খুব জরুরী কিছু না হলে কিছু এন্ট্রি দিবেননা।';
        $w[142]   = 'e|Requesition ID number already used.';
        $w[143]   = 's|Product Requisiton saved.';
        $w[144]   = 's|Voucher update successfully.';
        $w[145]   = 'e|You are not authorize to remove it.';
        $w[146]   = 'e|You are not authorize to @1@.';
        $w[149]   = 'e|This voucher not manually removeable.';
        $w[150]   = 'i|@1@ remove successfully';
        $w[151]   = 'i|This Ledger opening balance not editable.';
        $w[152]   = 'e|All Branch Debit and credit must be equal.';
        $w[152]   = 'e|Data not found.';

        $w[222]   = '<h3>Data not found</h3>';
        $w[223]   = 'e|Please upload .jpg file.';
        $w[224]   = 'e|Invalid password.';
        $w[225]   = 's|Password Change successfully.';
        $w[226]   = 's|You request link expired Please contact 01730912895.';
        $w[227]   = 'e|File size maximum 2000KB';
        return $w[$messageId];
    }
    function show_msg($jsonShow='No'){
        $jsonShow=strtolower($jsonShow);
        if($jsonShow=='yes'){$rt=array();}
        if(isset($_SESSION['msg'])){
            $sc = count($_SESSION['msg']);
            for($i=0; $i<$sc; $i++){
                $mCode = $_SESSION['msg'][$i];
                if(!is_array($mCode)){
                    $ms = m($mCode);
                    $m = explode('|',$ms);get_message($m[1],$m[0]);
                }
                else{
                    $co = count($mCode);
                    $ms = m($mCode[0]);
                    $m = explode('|',$ms);
                    $mainMessage = $m[1];
                    for($j=1; $j<$co; $j++){$mainMessage = str_ireplace('@'.$j.'@',$mCode[$j],$mainMessage);}
                    if($jsonShow!='yes'){get_message($mainMessage,$m[0]);}
                    else{$rt[]=array($m[0],$mainMessage);}
                }
            }
            unset($_SESSION['msg']);
        }
        if($jsonShow=='yes'){return $rt;}
    }
    function get_message($msg, $cat,$no='',$doNotHide = ''){
        if ($cat == 'w'){
            $type='warning';
            $t = "Warning";
        }
        elseif ($cat == 's'){
            $type='success';
            $t = 'Success';
        }
        elseif ($cat == 'e'){
            $type='failure';
            $t = 'Error';
        }
        else{
            $type='information';
            $t = 'Information';
        }

        if($doNotHide){ $hide = ''; } else{ $hide = 'hideit'; }
        if($no){$msgNo = $t.' No '.$no.': ';}else{$msgNo = '';}
    ?>

    <div class="notification <?=$type?> <?=$hide?>">
        <p><b><?=$msgNo.$msg?></b>
        </p>
    </div>
    <?php
    }
    function SetMessage($msgNo){
        if(!is_array($msgNo)){$msgNo=func_get_args();}
        $_SESSION['msg'][] = $msgNo;
    }
?>
