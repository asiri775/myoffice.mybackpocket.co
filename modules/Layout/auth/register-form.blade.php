<form class="form bravo-form-register themeForm" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">First Name
                    <span class=" required">*</span></label>
                <input type="text" class="form-control" name="first_name" autocomplete="off" placeholder="First Name">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-first_name"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Last Name
                    <span class=" required">*</span></label>
                <input type="text" class="form-control" name="last_name" autocomplete="off" placeholder="Last Name">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-last_name"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Country of Residence
            <span class=" required">*</span></label>
        <select id="country" class="formselect form-control" style="width:100%" data-placeholder="Select a Country">
            <option value="39" selected="selected">Canada</option>
            <option value="227">United States</option>
            <option value="1">Afghanistan</option>
            <option value="2">Albania</option>
            <option value="3">Algeria</option>
            <option value="4">American Samoa</option>
            <option value="5">Andorra</option>
            <option value="6">Angola</option>
            <option value="7">Anguilla</option>
            <option value="8">Antarctica</option>
            <option value="9">Antigua and Barbuda</option>
            <option value="10">Argentina</option>
            <option value="11">Armenia</option>
            <option value="12">Aruba</option>
            <option value="13">Australia</option>
            <option value="14">Austria</option>
            <option value="15">Azerbaijan</option>
            <option value="16">Bahamas</option>
            <option value="17">Bahrain</option>
            <option value="18">Bangladesh</option>
            <option value="19">Barbados</option>
            <option value="20">Belarus</option>
            <option value="21">Belgium</option>
            <option value="22">Belize</option>
            <option value="23">Benin</option>
            <option value="24">Bermuda</option>
            <option value="25">Bhutan</option>
            <option value="26">Bolivia</option>
            <option value="27">Bosnia and Herzegovina</option>
            <option value="28">Botswana</option>
            <option value="29">Bouvet Island</option>
            <option value="30">Brazil</option>
            <option value="31">British Indian Ocean Territory</option>
            <option value="32">British Virgin Islands</option>
            <option value="33">Brunei</option>
            <option value="34">Bulgaria</option>
            <option value="35">Burkina Faso</option>
            <option value="36">Burundi</option>
            <option value="37">Cambodia</option>
            <option value="38">Cameroon</option>
            <option value="40">Cape Verde</option>
            <option value="41">Cayman Islands</option>
            <option value="42">Central African Republic</option>
            <option value="43">Chad</option>
            <option value="44">Chile</option>
            <option value="45">China</option>
            <option value="46">Christmas Island</option>
            <option value="47">Cocos Islands</option>
            <option value="48">Colombia</option>
            <option value="49">Comoros</option>
            <option value="50">Cook Islands</option>
            <option value="51">Costa Rica</option>
            <option value="52">Croatia</option>
            <option value="53">Cuba</option>
            <option value="54">Cyprus</option>
            <option value="55">Czech Republic</option>
            <option value="56">Democratic Republic of the Congo</option>
            <option value="57">Denmark</option>
            <option value="58">Djibouti</option>
            <option value="59">Dominica</option>
            <option value="60">Dominican Republic</option>
            <option value="61">East Timor</option>
            <option value="62">Ecuador</option>
            <option value="63">Egypt</option>
            <option value="64">El Salvador</option>
            <option value="65">Equatorial Guinea</option>
            <option value="66">Eritrea</option>
            <option value="67">Estonia</option>
            <option value="68">Ethiopia</option>
            <option value="69">Falkland Islands</option>
            <option value="70">Faroe Islands</option>
            <option value="71">Fiji</option>
            <option value="72">Finland</option>
            <option value="73">France</option>
            <option value="74">French Guiana</option>
            <option value="75">French Polynesia</option>
            <option value="76">French Southern Territories</option>
            <option value="77">Gabon</option>
            <option value="78">Gambia</option>
            <option value="79">Georgia</option>
            <option value="80">Germany</option>
            <option value="81">Ghana</option>
            <option value="82">Gibraltar</option>
            <option value="83">Greece</option>
            <option value="84">Greenland</option>
            <option value="85">Grenada</option>
            <option value="86">Guadeloupe</option>
            <option value="87">Guam</option>
            <option value="88">Guatemala</option>
            <option value="89">Guinea</option>
            <option value="90">Guinea-Bissau</option>
            <option value="91">Guyana</option>
            <option value="92">Haiti</option>
            <option value="93">Heard Island and McDonald Islands</option>
            <option value="94">Honduras</option>
            <option value="95">Hong Kong</option>
            <option value="96">Hungary</option>
            <option value="97">Iceland</option>
            <option value="98">India</option>
            <option value="99">Indonesia</option>
            <option value="100">Iran</option>
            <option value="101">Iraq</option>
            <option value="102">Ireland</option>
            <option value="103">Israel</option>
            <option value="104">Italy</option>
            <option value="105">Ivory Coast</option>
            <option value="106">Jamaica</option>
            <option value="107">Japan</option>
            <option value="108">Jordan</option>
            <option value="109">Kazakhstan</option>
            <option value="110">Kenya</option>
            <option value="111">Kiribati</option>
            <option value="112">Kuwait</option>
            <option value="113">Kyrgyzstan</option>
            <option value="114">Laos</option>
            <option value="115">Latvia</option>
            <option value="116">Lebanon</option>
            <option value="117">Lesotho</option>
            <option value="118">Liberia</option>
            <option value="119">Libya</option>
            <option value="120">Liechtenstein</option>
            <option value="121">Lithuania</option>
            <option value="122">Luxembourg</option>
            <option value="123">Macao</option>
            <option value="124">Macedonia</option>
            <option value="125">Madagascar</option>
            <option value="126">Malawi</option>
            <option value="127">Malaysia</option>
            <option value="128">Maldives</option>
            <option value="129">Mali</option>
            <option value="130">Malta</option>
            <option value="131">Marshall Islands</option>
            <option value="132">Martinique</option>
            <option value="133">Mauritania</option>
            <option value="134">Mauritius</option>
            <option value="135">Mayotte</option>
            <option value="136">Mexico</option>
            <option value="137">Micronesia</option>
            <option value="138">Moldova</option>
            <option value="139">Monaco</option>
            <option value="140">Mongolia</option>
            <option value="141">Montserrat</option>
            <option value="142">Morocco</option>
            <option value="143">Mozambique</option>
            <option value="144">Myanmar</option>
            <option value="145">Namibia</option>
            <option value="146">Nauru</option>
            <option value="147">Nepal</option>
            <option value="148">Netherlands</option>
            <option value="149">Netherlands Antilles</option>
            <option value="150">New Caledonia</option>
            <option value="151">New Zealand</option>
            <option value="152">Nicaragua</option>
            <option value="153">Niger</option>
            <option value="154">Nigeria</option>
            <option value="155">Niue</option>
            <option value="156">Norfolk Island</option>
            <option value="157">North Korea</option>
            <option value="158">Northern Mariana Islands</option>
            <option value="159">Norway</option>
            <option value="160">Oman</option>
            <option value="161">Pakistan</option>
            <option value="162">Palau</option>
            <option value="163">Palestinian Territory</option>
            <option value="164">Panama</option>
            <option value="165">Papua New Guinea</option>
            <option value="166">Paraguay</option>
            <option value="167">Peru</option>
            <option value="168">Philippines</option>
            <option value="169">Pitcairn</option>
            <option value="170">Poland</option>
            <option value="171">Portugal</option>
            <option value="172">Puerto Rico</option>
            <option value="173">Qatar</option>
            <option value="174">Republic of the Congo</option>
            <option value="175">Reunion</option>
            <option value="176">Romania</option>
            <option value="177">Russia</option>
            <option value="178">Rwanda</option>
            <option value="179">Saint Helena</option>
            <option value="180">Saint Kitts and Nevis</option>
            <option value="181">Saint Lucia</option>
            <option value="182">Saint Pierre and Miquelon</option>
            <option value="183">Saint Vincent and the Grenadines</option>
            <option value="184">Samoa</option>
            <option value="185">San Marino</option>
            <option value="186">Sao Tome and Principe</option>
            <option value="187">Saudi Arabia</option>
            <option value="188">Senegal</option>
            <option value="189">Serbia and Montenegro</option>
            <option value="190">Seychelles</option>
            <option value="191">Sierra Leone</option>
            <option value="192">Singapore</option>
            <option value="193">Slovakia</option>
            <option value="194">Slovenia</option>
            <option value="195">Solomon Islands</option>
            <option value="196">Somalia</option>
            <option value="197">South Africa</option>
            <option value="198">South Georgia and the South Sandwich Islands</option>
            <option value="199">South Korea</option>
            <option value="200">Spain</option>
            <option value="201">Sri Lanka</option>
            <option value="202">Sudan</option>
            <option value="203">Suriname</option>
            <option value="204">Svalbard and Jan Mayen</option>
            <option value="205">Swaziland</option>
            <option value="206">Sweden</option>
            <option value="207">Switzerland</option>
            <option value="208">Syria</option>
            <option value="209">Taiwan</option>
            <option value="210">Tajikistan</option>
            <option value="211">Tanzania</option>
            <option value="212">Thailand</option>
            <option value="213">Togo</option>
            <option value="214">Tokelau</option>
            <option value="215">Tonga</option>
            <option value="216">Trinidad and Tobago</option>
            <option value="217">Tunisia</option>
            <option value="218">Turkey</option>
            <option value="219">Turkmenistan</option>
            <option value="220">Turks and Caicos Islands</option>
            <option value="221">Tuvalu</option>
            <option value="222">U.S. Virgin Islands</option>
            <option value="223">Uganda</option>
            <option value="224">Ukraine</option>
            <option value="225">United Arab Emirates</option>
            <option value="226">United Kingdom</option>
            <option value="228">United States Minor Outlying Islands</option>
            <option value="229">Uruguay</option>
            <option value="230">Uzbekistan</option>
            <option value="231">Vanuatu</option>
            <option value="232">Vatican</option>
            <option value="233">Venezuela</option>
            <option value="234">Vietnam</option>
            <option value="235">Wallis and Futuna</option>
            <option value="236">Western Sahara</option>
            <option value="237">Yemen</option>
            <option value="238">Zambia</option>
            <option value="239">Zimbabwe</option>
        </select>
    </div>

    <div class="form-group">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Email
            <span class=" required">*</span></label>
        <input type="email" class="form-control" name="email" autocomplete="off" placeholder="Email address">
        <i class="input-icon field-icon icofont-mail"></i>
        <span class="invalid-feedback error error-email"></span>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group password">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Password
                    <span class=" required">*</span></label>
                <input type="password" class="form-control" name="password" autocomplete="off"
                    placeholder="Password">
                <i class="input-icon field-icon icofont-ui-password"></i>
                <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                <span class="invalid-feedback error error-password"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group password">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Confirm Password
                    <span class=" required">*</span></label>
                <input type="password" class="form-control" name="password" autocomplete="off"
                    placeholder="Confirm Password">
                <i class="input-icon field-icon icofont-ui-password"></i>
                <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                <span class="invalid-feedback error error-password"></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Referral Code</label>
        <input type="text" value="<?= $_GET['ref'] ?? '' ?>" class="form-control" name="ref"
            autocomplete="off" placeholder="Enter Referral Code to Win Credits">
        <i class="input-icon field-icon icofont-mail"></i>
        <span class="invalid-feedback error error-email"></span>
    </div>

    <div class="error message-error invalid-feedback"></div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary form-submit">
            Sign Up
            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>
    <div class="advanced">
        <p class="text-center f14 c-grey">Already a MyOffice member? <a href="javascript:;"
                class="signinclickmain gray-text">Log
                In</a></p>
    </div>

    @if (setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="socialAuthStages">
            <div class="loginrow orPlx fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                <span>or</span>
            </div>
            <div class="fontsize12 graytext text-center mb-3">Continue With</div>
            <div id="wrapper" style="text-align: center;">
                <div style="display: inline-block; vertical-align: top;">
                    <a href="{{ route('social.login', 'facebook') }}">
                        <div class="text left pr-3">
                            <img src="/images/facebook.png">
                            <p class="robotoregular fontsize11 graytext text-uppercase mt-2">Facebook
                            </p>
                        </div>
                    </a>
                </div>
                <div style="display: inline-block; vertical-align: top;">
                    <a href="{{ route('social.login', 'google') }}"">
                        <div class="text-center left">
                            <img src="/images/google.png">
                            <p class="robotoregular fontsize11 graytext text-uppercase mt-2">Google+</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif


</form>
