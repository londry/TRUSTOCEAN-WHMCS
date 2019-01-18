<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>
<div class="section">
    <div class="section-header">
        <h3 style="padding-left: 22px;"><i class="oi" data-glyph="shield" style="margin-left: -23px;margin-top: 2px; position: absolute; padding-bottom: 3px;"></i> 提交企业信息进行验证</h3>
        <p>请您仔细填写和营业执照一致的企业信息, 中文企业直接填写中文语言, 英文或其他语言请直接填写同营业执照一致的语言信息</p>
        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        {if $vars.info}
            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        <div class="main-content col-md-12 pull-md-right">
        <script type="text/javascript" src="/assets/js/StatesDropdown.js"></script>
        <form method="post" action="{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=enroll" role="form">
        <h3>基本信息</h3>
        <input name="toca" value="updateorganizationinfo" hidden>
        <div class="panel panel-default panel-form">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="organizationName" class="control-label">企业名称</label>
                            <input type="text" required name="organization_name" id="organizationName" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="registeredNo" class="control-label">企业编号</label>
                            <input type="text" required name="registered_no" id="registeredNo" placeholder="统一社会信用代码/组织机构代码/Company Number" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="organizationalUnitName" class="control-label">部门名称</label>
                            <input type="text" required name="organizationalUnitName" id="organizationalUnitName" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="dunsNumber" class="control-label">DUNS代码(选填)</label>
                            <input type="text" name="dunsNumber" id="dunsNumber" placeholder="邓白氏9位数代码" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="assumedName" class="control-label">DBA名称(选填)</label>
                            <input type="text" name="assumedName" id="assumedName" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="registeredAddressLine1" class="control-label">注册地址</label>
                            <input type="text" required name="registered_address_line1" id="registeredAddressLine1" value="" placeholder="和执照地址一致" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="dateOfIncorporation" class="control-label">注册日期</label>
                            <input type="text" required name="date_of_incorporation" id="dateOfIncorporation" value="" placeholder="格式: 2015-10-01" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="m-t-16">联系信息</h3>
        <div class="panel panel-default panel-form">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="country">国家</label>
                            <select name="country" required id="country" class="form-control"<option value="AF">Afghanistan</option><option value="AX">Aland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua And Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia And Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN" selected="selected">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CD">Congo, Democratic Republic</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Cote D'Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curacao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island &amp; Mcdonald Islands</option><option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran, Islamic Republic Of</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle Of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KR">Korea</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States Of</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestine, State of</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option><option value="BL">Saint Barthelemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts And Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin</option><option value="PM">Saint Pierre And Miquelon</option><option value="VC">Saint Vincent And Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome And Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia And Sandwich Isl.</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard And Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad And Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks And Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela</option><option value="VN">Viet Nam</option><option value="VG">Virgin Islands, British</option><option value="VI">Virgin Islands, U.S.</option><option value="WF">Wallis And Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputState" class="control-label">省份</label>
                                    <input type="text" id="stateinput" value="Shaanxi" class="form-control" style="display: none;">
                                <select name="state" required class="form-control" id="stateselect" required="required"><option value="">—</option><option>Anhui</option><option>Beijing</option><option>Fujian</option><option>Gansu</option><option>Guangdong</option><option>Guizhou</option><option>Hainan</option><option>Hebei</option><option>Heilongjiang</option><option>Henan</option><option>Hubei</option><option>Hunan</option><option>Jiangsu</option><option>Jiangxi</option><option>Jilin</option><option>Liaoning</option><option>Qinghai</option><option selected="selected">Shaanxi</option><option>Shandong</option><option>Shanghai</option><option>Shanxi</option><option>Sichuan</option><option>Tianjin</option><option>Xizang</option><option>Yunnan</option><option>Zhejiang</option></select></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="postalCode" class="control-label">邮编</label>
                                    <input type="text" required name="postal_code" id="postalCode" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="city" class="control-label">城市</label>
                            <input type="text" required name="city" id="city" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="callbackPhone" class="control-label">企业联系电话</label>
                            <input type="text" required name="organization_phone" id="callbackPhone" value="" placeholder="010 8588 8888" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3>申请联系人</h3>
        <p>必须为申请认证的企业内部职员或企业负责人, 该联系人需经过企业管理人或所有者授权，在企业信息预验证过程中, 我们将会优先联系下方填写的联系人</p>
        <div class="panel panel-default panel-form">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactName" class="control-label">姓名</label>
                            <input type="text" required name="contact_name" id="contactName" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactTitle" class="control-label">职位</label>
                            <input type="text" required name="contact_title" id="contactTitle" placeholder="企业内职位或职称" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactPhone" class="control-label">联系电话</label>
                            <input type="text" required name="contact_phone" id="contactPhone" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactEmail" class="control-label">电子邮箱</label>
                            <input type="email" required name="contact_email" id="contactEmail" value="" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
        <input class="btn btn-primary" type="submit" name="save" value="保存提交">
        </div>
        </form>

        </div>
    </div>
</div>