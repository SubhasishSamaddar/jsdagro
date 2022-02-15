<?php

use Illuminate\Database\Seeder;
use App\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     
    public function run()
    {
        $states = [
            ["Alabama","AL","235"],
            ["Alaska","AK","235"],
            ["Arizona","AZ","235"], 
            ["Arkansas","AR","235"], 
            ["California","CA","235"],
            ["Colorado","CO","235"],
            ["Connecticut","CT","235"],
            ["Delaware","DE","235"],
            ["District of Columbia","DC","235"],
            ["Florida","FL","235"],
            ["Georgia","GA","235"],
            ["Hawaii","HI","235"],
            ["Idaho","ID","235"],
            ["Illinois","IL","235"],
            ["Indiana","IN","235"],
            ["Iowa","IA","235"],
            ["Kansas","KS","235"],
            ["Kentucky","KY","235"],
            ["Louisiana","LA","235"],
            ["Maine","ME","235"],
            ["Maryland","MD","235"],
            ["Massachusetts","MA","235"],
            ["Michigan","MI","235"],
            ["Minnesota","MN","235"],
            ["Mississippi","MS","235"],
            ["Missouri","MO","235"],
            ["Montana","MT","235"],
            ["Nebraska","NE","235"],
            ["Nevada","NV","235"],
            ["New Hampshire","NH","235"],
            ["New Jersey","NJ","235"],
            ["New Mexico","NM","235"],
            ["New York","NY","235"],
            ["North Carolina","NC","235"],
            ["North Dakota","ND","235"],
            ["Ohio","OH","235"],
            ["Oklahoma","OK","235"],
            ["Oregon","OR","235"],
            ["Pennsylvania","PA","235"],
            ["Rhode Island","RI","235"],
            ["South Carolina","SC","235"],
            ["South Dakota","SD","235"],
            ["Tennessee","TN","235"],
            ["Texas","TX","235"],
            ["Utah","UT","235"],
            ["Vermont","VT","235"],
            ["Virginia","VA","235"],
            ["Washington","WA","235"],
            ["West Virginia","WV","235"],
            ["Wisconsin","WI","235"],
            ["Wyoming","WY","235"],
            ["Andaman and Nicobar Islands","AN","103"],
            ["Andhra Pradesh","AP","103"],
            ["Arunachal Pradesh","AR","103"],
            ["Assam","AS","103"],
            ["Bihar","BR","103"],
            ["Chandigarh","CH","103"],
            ["Chhattisgarh","CT","103"],
            ["Dadra and Nagar Haveli","DN","103"],
            ["Daman and Diu","DD","103"],
            ["Delhi","DL","103"],
            ["Goa","GA","103"],
            ["Gujarat","GJ","103"],
            ["Haryana","HR","103"],
            ["Himachal Pradesh","HP","103"],
            ["Jammu and Kashmir","JK","103"],
            ["Jharkhand","JH","103"],
            ["Karnataka","KA","103"],
            ["Kerala","KL","103"],
            ["Ladakh","LA","103"],
            ["Lakshadweep","LD","103"],
            ["Madhya Pradesh","MP","103"],
            ["Maharashtra","MH","103"],
            ["Manipur","MN","103"],
            ["Meghalaya","ML","103"],
            ["Mizoram","MZ","103"],
            ["Nagaland","NL","103"],
            ["Odisha","OR","103"],
            ["Puducherry","PY","103"],
            ["Punjab","PB","103"],
            ["Rajasthan","RJ","103"],
            ["Sikkim","SK","103"],
            ["Tamil Nadu","TN","103"],
            ["Telangana","TG","103"],
            ["Tripura","TR","103"],
            ["Uttar Pradesh","UP","103"],
            ["Uttarakhand","UT","103"],
            ["West Bengal","WB","103"] 
        ];
        
        foreach ($states as $state) {
            State::create(
                [
                        'state_name' => $state[0],
                        'state_code' => $state[1],
                        'country_id' => $state[2] 
                ]);
       }
    }
}
