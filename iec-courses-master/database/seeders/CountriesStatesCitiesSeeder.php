<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class CountriesStatesCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('cities')->delete();
        DB::table('states')->delete();
        DB::table('countries')->delete();

        // Define countries with their states and cities
        $countries = [
            'Pakistan' => [
                'Punjab' => ['Lahore', 'Faisalabad', 'Rawalpindi', 'Multan', 'Gujranwala', 'Sialkot', 'Bahawalpur', 'Sargodha', 'Sheikhupura', 'Jhang'],
                'Sindh' => ['Karachi', 'Hyderabad', 'Sukkur', 'Larkana', 'Nawabshah', 'Mirpur Khas', 'Jacobabad', 'Shikarpur', 'Khairpur', 'Dadu'],
                'Khyber Pakhtunkhwa' => ['Peshawar', 'Abbottabad', 'Mardan', 'Swat', 'Bannu', 'Kohat', 'Dera Ismail Khan', 'Mansehra', 'Nowshera', 'Charsadda'],
                'Balochistan' => ['Quetta', 'Gwadar', 'Turbat', 'Khuzdar', 'Sibi', 'Chaman', 'Zhob', 'Loralai', 'Dera Bugti', 'Ziarat'],
                'Gilgit-Baltistan' => ['Gilgit', 'Skardu', 'Hunza', 'Astore', 'Ghanche', 'Diamer', 'Ghizer', 'Nagar', 'Shigar', 'Kharmang'],
                'Azad Kashmir' => ['Muzaffarabad', 'Mirpur', 'Kotli', 'Rawalakot', 'Bagh', 'Bhimber', 'Hattian', 'Neelum', 'Haveli', 'Sudhnuti']
            ],
            'United Arab Emirates' => [
                'Abu Dhabi' => ['Abu Dhabi City', 'Al Ain', 'Ruwais', 'Liwa Oasis', 'Delma Island', 'Madinat Zayed', 'Al Dhafra', 'Al Mirfa', 'Al Sila', 'Ghayathi'],
                'Dubai' => ['Dubai City', 'Jebel Ali', 'Deira', 'Bur Dubai', 'Palm Jumeirah', 'Downtown Dubai', 'Dubai Marina', 'Al Barsha', 'Al Quoz', 'Mirdif'],
                'Sharjah' => ['Sharjah City', 'Khor Fakkan', 'Kalba', 'Dibba Al-Hisn', 'Al Dhaid', 'Al Madam', 'Al Batayeh', 'Al Hamriyah', 'Al Qasimia', 'Al Nahda'],
                'Ajman' => ['Ajman City', 'Al Manama', 'Masfout', 'Al Hamidiyah', 'Al Zorah', 'Al Rashidiya', 'Al Nuaimiya', 'Al Mowaihat', 'Al Jurf', 'Al Rawda'],
                'Umm Al Quwain' => ['Umm Al Quwain City', 'Al Salamah', 'Al Raas', 'Al Ramlah', 'Al Haditha', 'Al Humrah', 'Al Khor', 'Al Siniyah', 'Al Qarain', 'Al Shabakah'],
                'Ras Al Khaimah' => ['Ras Al Khaimah City', 'Al Jazirah Al Hamra', 'Al Rams', 'Digdaga', 'Khatt', 'Al Hamraniyah', 'Al Ghayl', 'Al Hudaibah', 'Al Mairid', 'Al Sawan'],
                'Fujairah' => ['Fujairah City', 'Dibba', 'Masafi', 'Madhab', 'Sakamkam', 'Al Bithnah', 'Al Qurayyah', 'Al Taween', 'Al Siji', 'Al Hayl']
            ],
            'Saudi Arabia' => [
                'Riyadh' => ['Riyadh', 'Diriyah', 'Al Kharj', 'Al Majma\'ah', 'Al Duwadimi', 'Al Quway\'iyah', 'Al Aflaj', 'Al Zulfi', 'Al Ghat', 'Al Sulayyil'],
                'Mecca' => ['Mecca', 'Jeddah', 'Taif', 'Al Bahah', 'Al Qunfudhah', 'Al Lith', 'Rabigh', 'Al Jumum', 'Al Khurmah', 'Al Moya'],
                'Medina' => ['Medina', 'Yanbu', 'Badr', 'Khaybar', 'Al Ula', 'Mahd adh Dhahab', 'Al Henakiyah', 'Al Mastourah', 'Al E\'s', 'Wadi al-Fara\''],
                'Eastern Province' => ['Dammam', 'Al Khobar', 'Dhahran', 'Jubail', 'Hafr Al-Batin', 'Al Qatif', 'Ras Tanura', 'Abqaiq', 'Al Nairyah', 'Al Khafji'],
                'Asir' => ['Abha', 'Khamis Mushait', 'Bisha', 'Najran', 'Sharurah', 'Al Namas', 'Tathlith', 'Rijal Alma\'a', 'Al Majaridah', 'Al Harajah']
            ],
            'United Kingdom' => [
                'England' => ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds', 'Sheffield', 'Bristol', 'Leicester', 'Nottingham', 'Coventry'],
                'Scotland' => ['Edinburgh', 'Glasgow', 'Aberdeen', 'Dundee', 'Inverness', 'Stirling', 'Perth', 'Dunfermline', 'Ayr', 'Falkirk'],
                'Wales' => ['Cardiff', 'Swansea', 'Newport', 'Bangor', 'St Davids', 'Aberystwyth', 'Wrexham', 'Llandudno', 'Caernarfon', 'Conwy'],
                'Northern Ireland' => ['Belfast', 'Derry', 'Lisburn', 'Newry', 'Armagh', 'Enniskillen', 'Omagh', 'Coleraine', 'Carrickfergus', 'Ballymena']
            ],
            'United States' => [
                'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento', 'San Jose', 'Fresno', 'Long Beach', 'Oakland', 'Anaheim', 'Santa Ana'],
                'New York' => ['New York City', 'Buffalo', 'Rochester', 'Albany', 'Syracuse', 'Yonkers', 'New Rochelle', 'Mount Vernon', 'Schenectady', 'Utica'],
                'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth', 'El Paso', 'Arlington', 'Corpus Christi', 'Plano', 'Laredo'],
                'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville', 'Fort Lauderdale', 'St. Petersburg', 'Hialeah', 'Port St. Lucie', 'Cape Coral', 'Tallahassee'],
                'Illinois' => ['Chicago', 'Springfield', 'Peoria', 'Rockford', 'Naperville', 'Aurora', 'Joliet', 'Elgin', 'Waukegan', 'Cicero']
            ],
            'Canada' => [
                'Ontario' => ['Toronto', 'Ottawa', 'Hamilton', 'London', 'Windsor', 'Kitchener', 'Brampton', 'Mississauga', 'Markham', 'Vaughan'],
                'Quebec' => ['Montreal', 'Quebec City', 'Laval', 'Gatineau', 'Longueuil', 'Sherbrooke', 'Saguenay', 'Lévis', 'Trois-Rivières', 'Terrebonne'],
                'British Columbia' => ['Vancouver', 'Victoria', 'Surrey', 'Burnaby', 'Richmond', 'Kelowna', 'Abbotsford', 'Coquitlam', 'Saanich', 'Delta'],
                'Alberta' => ['Calgary', 'Edmonton', 'Red Deer', 'Lethbridge', 'Medicine Hat', 'Grande Prairie', 'Airdrie', 'Spruce Grove', 'Leduc', 'Fort Saskatchewan'],
                'Manitoba' => ['Winnipeg', 'Brandon', 'Thompson', 'Portage la Prairie', 'Selkirk', 'Steinbach', 'Winkler', 'Dauphin', 'Morden', 'The Pas']
            ],
            'Australia' => [
                'New South Wales' => ['Sydney', 'Newcastle', 'Wollongong', 'Central Coast', 'Blue Mountains', 'Albury', 'Armidale', 'Bathurst', 'Broken Hill', 'Dubbo'],
                'Victoria' => ['Melbourne', 'Geelong', 'Ballarat', 'Bendigo', 'Shepparton', 'Mildura', 'Warrnambool', 'Wodonga', 'Traralgon', 'Wangaratta'],
                'Queensland' => ['Brisbane', 'Gold Coast', 'Sunshine Coast', 'Townsville', 'Cairns', 'Toowoomba', 'Mackay', 'Rockhampton', 'Bundaberg', 'Hervey Bay'],
                'Western Australia' => ['Perth', 'Fremantle', 'Mandurah', 'Bunbury', 'Geraldton', 'Kalgoorlie', 'Albany', 'Broome', 'Port Hedland', 'Karratha'],
                'South Australia' => ['Adelaide', 'Mount Gambier', 'Whyalla', 'Murray Bridge', 'Port Augusta', 'Port Lincoln', 'Port Pirie', 'Victor Harbor', 'Gawler', 'Roxby Downs']
            ],
            'India' => [
                'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Nashik', 'Aurangabad', 'Solapur', 'Amravati', 'Kolhapur', 'Nanded', 'Sangli'],
                'Delhi' => ['New Delhi', 'Noida', 'Gurgaon', 'Faridabad', 'Ghaziabad', 'Greater Noida', 'Sonipat', 'Panipat', 'Karnal', 'Rohtak'],
                'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Gulbarga', 'Davanagere', 'Bellary', 'Bijapur', 'Shimoga'],
                'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 'Tiruppur', 'Erode', 'Vellore', 'Thoothukudi'],
                'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Agra', 'Varanasi', 'Allahabad', 'Meerut', 'Ghaziabad', 'Aligarh', 'Moradabad', 'Saharanpur']
            ],
            'Bangladesh' => [
                'Dhaka' => ['Dhaka', 'Narayanganj', 'Gazipur', 'Savar', 'Tongi', 'Keraniganj', 'Narsingdi', 'Manikganj', 'Munshiganj', 'Faridpur'],
                'Chittagong' => ['Chittagong', 'Cox\'s Bazar', 'Comilla', 'Feni', 'Brahmanbaria', 'Chandpur', 'Lakshmipur', 'Noakhali', 'Rangamati', 'Bandarban'],
                'Khulna' => ['Khulna', 'Jessore', 'Satkhira', 'Bagerhat', 'Chuadanga', 'Jhenaidah', 'Kushtia', 'Magura', 'Meherpur', 'Narail'],
                'Rajshahi' => ['Rajshahi', 'Bogra', 'Pabna', 'Sirajganj', 'Natore', 'Naogaon', 'Joypurhat', 'Chapai Nawabganj', 'Kushtia', 'Meherpur'],
                'Sylhet' => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj', 'Kishoreganj', 'Netrokona', 'Sherpur', 'Jamalpur', 'Mymensingh', 'Tangail']
            ],
            'Sri Lanka' => [
                'Western' => ['Colombo', 'Gampaha', 'Kalutara', 'Negombo', 'Moratuwa', 'Panadura', 'Wattala', 'Kaduwela', 'Kesbewa', 'Maharagama'],
                'Central' => ['Kandy', 'Matale', 'Nuwara Eliya', 'Dambulla', 'Gampola', 'Nawalapitiya', 'Hatton', 'Talawakele', 'Kegalle', 'Mawanella'],
                'Southern' => ['Galle', 'Matara', 'Hambantota', 'Weligama', 'Tangalle', 'Ambalangoda', 'Hikkaduwa', 'Balapitiya', 'Bentota', 'Mirissa'],
                'Northern' => ['Jaffna', 'Vavuniya', 'Mannar', 'Kilinochchi', 'Mullaitivu', 'Point Pedro', 'Chavakachcheri', 'Valvettithurai', 'Nallur', 'Karainagar'],
                'Eastern' => ['Batticaloa', 'Trincomalee', 'Ampara', 'Kalmunai', 'Akkaraipattu', 'Sammanthurai', 'Kattankudy', 'Eravur', 'Valaichchenai', 'Chenkalady']
            ]
        ];

        // Insert countries and their states/cities
        foreach ($countries as $countryName => $states) {
            // Check if country exists
            $country = Country::firstOrCreate(
                ['name' => $countryName],
                ['code' => $this->getCountryCode($countryName)]
            );

            foreach ($states as $stateName => $cities) {
                // Check if state exists
                $state = State::firstOrCreate(
                    ['name' => $stateName, 'country_id' => $country->id]
                );

                foreach ($cities as $cityName) {
                    // Check if city exists
                    City::firstOrCreate(
                        ['name' => $cityName, 'state_id' => $state->id, 'country_id' => $country->id]
                    );
                }
            }
        }
    }

    private function getCountryCode($countryName)
    {
        $codes = [
            'Pakistan' => 'PK',
            'United Arab Emirates' => 'AE',
            'Saudi Arabia' => 'SA',
            'United Kingdom' => 'GB',
            'United States' => 'US',
            'Canada' => 'CA',
            'Australia' => 'AU',
            'India' => 'IN',
            'Bangladesh' => 'BD',
            'Sri Lanka' => 'LK'
        ];

        return $codes[$countryName] ?? substr(strtoupper($countryName), 0, 2);
    }
}
