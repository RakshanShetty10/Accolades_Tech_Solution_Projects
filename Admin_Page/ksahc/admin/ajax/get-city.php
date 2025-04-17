<?php
$districts = [
    // Karnataka
    "Bagalkote", "Jamkhandi", "Mudhola", "Badami", "Bilagi", "Hunagunda", "Ilkal", "Rabkavi Banhatti", 
    "Guledgudda", "Ballari", "Kurugodu", "Kampli", "Sanduru", "Siraguppa", "Belagavi", "Athani", 
    "Bailhongal", "Chikkodi", "Gokak", "Khanapura", "Mudalgi", "Nippani", "Rayabaga", "Savadatti", 
    "Ramadurga", "Kagawada", "Hukkeri", "Kitturu", "Bengaluru Urban", "Hebbala", "Kengeri", 
    "Krishnarajapura", "Anekal", "Yelahanka", "Bengaluru Rural", "Nelamangala", "Doddaballapura", 
    "Devanahalli", "Hosakote", "Bidar", "Aurad", "Basavakalyana", "Bhalki", "Chitgoppa", "Hulsuru", 
    "Humnabad", "Kamalanagara", "Chamarajanagara", "Gundlupete", "Kollegala", "Yelanduru", 
    "Hanuru", "Chikkaballapura", "Bagepalli", "Chintamani", "Gauribidanuru", "Gudibanda", "Sidlaghatta", 
    "Cheluru", "Chikkamagaluru", "Kaduru", "Koppa", "Mudigere", "Narasimharajapura", "Sringeri", 
    "Tarikere", "Ajjampura", "Kalasa", "Chitradurga", "Challakere", "Hiriyur", "Holalkere", 
    "Hosadurga", "Molakalmuru", "Dakshina Kannada", "Mangaluru", "Ullal", "Mulki", "Moodbidri", 
    "Bantwala", "Belathangadi", "Putturu", "Sulya", "Kadaba", "Davanagere", "Harihara", "Channagiri", 
    "Honnali", "Nyamathi", "Jagaluru", "Kalghatgi", "Dharwad", "Hubballi (Rural)", "Hubballi (Urban)", 
    "Kundagolu", "Navalgunda", "Alnavara", "Annigeri", "Gadag", "Naragunda", "Mundaragi", "Rona", 
    "Gajendragada", "Lakshmeshwara", "Shirahatti", "Hassan", "Arasikere", "Channarayapattana", 
    "Holenarsipura", "Sakleshpura", "Aluru", "Arakalagudu", "Beluru", "Haveri", "Ranibennur", 
    "Byadgi", "Hangala", "Savanuru", "Hirekeruru", "Shiggavi", "Rattihalli", "Kalaburagi", 
    "Afzalpura", "Alanda", "Chincholi", "Chitapura", "Jevargi", "Sedam", "Kamalapura", "Shahabad", 
    "Kalgi", "Yedrami", "Kodagu", "Madikeri", "Somawarapete", "Virajapete", "Ponnammapete", 
    "Kushalnagara", "Kolar", "Bangarapete", "Maluru", "Mulabagilu", "Srinivasapura", 
    "Kolar Gold Fields (Robertsonpete)", "Koppala", "Gangavathi", "Kushtagi", "Yelaburga", 
    "Kanakagiri", "Karatagi", "Kukanuru", "Mandya", "Madduru", "Malavalli", "Srirangapattana", 
    "Krishnarajapete", "Nagamangala", "Pandavapura", "Mysuru", "Hunasuru", "Krishnarajanagara", 
    "Nanjanagodu", "Heggadadevanakote", "Piriyapattana", "Tirumakudalu Narasipura", 
    "Saraguru", "Saligrama", "Raichuru", "Sindhanuru", "Manvi", "Devadurga", "Lingasaguru", 
    "Mudgal", "Maski", "Sirawara", "Ramanagara", "Magadi", "Kanakapura", "Channapattana", "Harohalli", 
    "Shivamogga", "Sagara", "Bhadravathi", "Hosanagara", "Shikaripura", "Soraba", "Tirthahalli", 
    "Tumakuru", "Chikkanayakanahalli", "Kunigal", "Madhugiri", "Sira", "Tipturu", "Gubbi", "Koratagere", 
    "Pavagada", "Turuvekere", "Udupi", "Kapu", "Bynduru", "Karkala", "Kundapura", "Hebri", "Brahmavara", 
    "Uttara Kannada", "Karwara", "Sirsi", "Joida", "Dandeli", "Bhatkal", "Kumta", "Ankola", "Haliyal", 
    "Honnavara", "Mundagodu", "Siddapura", "Yellapura", "Vijayapura", "Indi", "Basavana Bagewadi", 
    "Sindgi", "Muddebihala", "Talikote", "Devara Hipparagi", "Chadchana", "Tikote", "Babaleshwara", 
    "Kolhara", "Nidagundi", "Alamela", "Yadagiri", "Shahapura", "Surapura", "Gurmitkala", "Vadagera", 
    "Hunsagi", "Vijayanagara", "Hosapete", "Hagaribommanahalli", "Harapanahalli", "Hoovina Hadagali", 
    "Kudligi", "Kotturu", "Chitradurga", "Doddaballapura", "Hosakote", "Shivaganga", "Navagraha Hills", 
    "Ramapura", "Shikarpur", "Bendigeri", "Vittal", "Nandikur", "Puttur", "Sullia", "Kukke Subramanya", 
    "Honnavar", "Nanjangud", "Savadatti", "Ainapur", "Dandeli", "Bhatkal", "Gundlupet", "Kolar", 
    "Bellary", "Nidagundi", "Chandragiri", "Channarayapatna", "Bhadravathi", "Talaghatta", 
    "Chikkahalli", "Bollare", "Bagepalli", "Bagalur", "Channarayapatna", "Savanuru", "Gundlupet", 
    "Banavara", "Shankarapuram", "Sindhanoor", "Saggur", "Alampur", "Challakere", "Mavinhalli", 
    "Thippanahalli", "Madhavapura", "Hirenandur", "Pampanadu", "Savalanga", "Shivapura", 
    "Kenchanakuppe", "Bichali", "Kudur", "Ramnagar", "Kempapura", "Hosur", "Bengaluru", 
    "Vengaluru", "Kolya", "Rayagada", "Heggadahalli", "Kavluru", "Tandavapura", "Bachhalli", 
    "Jagadenahalli", "Mallur", "Kumbalgodu", "Chikkanayakanahalli", "Hanchya", "Karur", 
    "Banavasi", "Siddapur", "Chikkanasandra", "Naganahalli", "Sadahalli", "Munjabettu", 
    "Yellapur", "Doddaballapura", "Navagraha", "Mithur", "Nayakanahatti", "Gubbi", "Shantigrama", 
    "Mulbagilu", "Kanakamajalu", "Pavithrapura", "Lakkavalli", "Chinthamani", "Koppalu", 
    "Shanivarsante", "Tadas", "Gundya", "Jamalabad", "Chikkanayakanahalli", "Jigani", "Hampi", 
    "Bukkapatna", "Pethalakere", "Kudige", "Togari", "Mallapura", "Tiptur", "Parvathipura", "Koratagere",

    // Kerala
    "Alappuzha", "Cherthala", "Haripad", "Kayamkulam", "Mavelikara", "Ambalappuzha", 
    "Ernakulam", "Kochi", "Aluva", "Perumbavoor", "Muvattupuzha", "Paravur", "Tripunithura", 
    "Idukki", "Thodupuzha", "Painavu", "Cheruthoni", "Munnar", "Kattappana", 
    "Kannur", "Thalassery", "Payyannur", "Mattannur", "Iritty", "Panoor", 
    "Kasaragod", "Kanhangad", "Bekal", "Nileshwaram", "Manjeshwar", 
    "Kollam", "Punalur", "Paravur", "Sasthamkotta", "Karunagappally", 
    "Kottayam", "Changanassery", "Pala", "Vaikom", "Ettumanoor", "Kumarakom", 
    "Kozhikode", "Vadakara", "Koyilandy", "Mukkam", "Feroke", "Beypore", 
    "Malappuram", "Manjeri", "Perinthalmanna", "Tirur", "Nilambur", "Kondotty", 
    "Palakkad", "Ottapalam", "Mannarkkad", "Shoranur", "Chittur", "Nenmara", 
    "Pathanamthitta", "Adoor", "Pandalam", "Thiruvalla", "Ranni", "Konni", 
    "Thiruvananthapuram", "Neyyattinkara", "Attingal", "Varkala", "Kattakada", "Kovalam", 
    "Thrissur", "Guruvayur", "Chavakkad", "Wadakkanchery", "Kunnamkulam", "Irinjalakuda", 
    "Wayanad", "Kalpetta", "Mananthavady", "Sulthan Bathery", "Meppadi",

    // Tamil Nadu
    "Ariyalur", "Udayarpalayam", "Jayankondam", 
    "Chengalpattu", "Tambaram", "Pallavaram", "Mahabalipuram", 
    "Chennai", "Egmore", "Mylapore", "T. Nagar", "Anna Nagar", "Kodambakkam", 
    "Coimbatore", "Pollachi", "Mettupalayam", "Valparai", "Sulur", 
    "Cuddalore", "Panruti", "Virudhachalam", "Neyveli", "Chidambaram", 
    "Dharmapuri", "Palacode", "Pennagaram", "Harur", 
    "Dindigul", "Palani", "Kodaikanal", "Oddanchatram", "Natham", 
    "Erode", "Gobichettipalayam", "Bhavani", "Perundurai", "Sathyamangalam", 
    "Kallakurichi", "Chinnasalem", "Ulundurpettai", 
    "Kancheepuram", "Sriperumbudur", "Uthiramerur", 
    "Karur", "Kulithalai", "Pugalur", 
    "Krishnagiri", "Hosur", "Denkanikottai", "Uthangarai", 
    "Madurai", "Melur", "Thirumangalam", "Usilampatti", "Vadipatti", 
    "Mayiladuthurai", "Sirkazhi", "Kuthalam", "Poompuhar", 
    "Nagapattinam", "Velankanni", "Vedaranyam", "Thirukkuvalai", 
    "Namakkal", "Rasipuram", "Tiruchengode", "Kumarapalayam", 
    "Nilgiris", "Ooty", "Coonoor", "Kotagiri", "Gudalur", 
    "Perambalur", "Kunnam", "Veppanthattai", 
    "Pudukottai", "Aranthangi", "Illupur", "Keeranur", 
    "Ramanathapuram", "Paramakudi", "Rameswaram", "Kamuthi", 
    "Ranipet", "Arcot", "Walajah", "Sholingur", 
    "Salem", "Attur", "Mettur", "Omalur", "Edappadi", 
    "Sivagangai", "Karaikudi", "Devakottai", "Manamadurai", 
    "Tenkasi", "Sankarankovil", "Courtallam", "Kadayanallur", 
    "Thanjavur", "Papanasam", "Kumbakonam", "Orathanadu", 
    "Theni", "Bodinayakkanur", "Cumbum", "Andipatti", 
    "Thoothukudi", "Tiruchendur", "Kovilpatti", "Ottapidaram", 
    "Tiruchirappalli", "Srirangam", "Lalgudi", "Manapparai", 
    "Tirunelveli", "Ambasamudram", "Cheranmahadevi", "Tenkasi", 
    "Tirupathur", "Vaniyambadi", "Ambur", "Jolarpet", 
    "Tiruppur", "Avinashi", "Palladam", "Udumalpet", "Dharapuram", 
    "Tiruvallur", "Ponneri", "Gummidipoondi", "Avadi", 
    "Tiruvannamalai", "Polur", "Chengam", "Arani", 
    "Tiruvarur", "Mannargudi", "Thiruthuraipoondi", 
    "Vellore", "Gudiyatham", "Katpadi", "Arakkonam", 
    "Viluppuram", "Gingee", "Tindivanam", "Kallakurichi", 
    "Virudhunagar", "Rajapalayam", "Sivakasi", "Sattur", "Aruppukkottai",

    // Maharashtra
    "Ahmednagar", "Shrirampur", "Kopargaon", "Sangamner", "Pathardi", 
    "Akola", "Murtizapur", "Telhara", 
    "Amravati", "Achalpur", "Warud", "Chandur Bazar", 
    "Aurangabad", "Paithan", "Vaijapur", "Gangapur", 
    "Beed", "Ambajogai", "Parli", "Keij", 
    "Bhandara", "Tumsar", "Sakoli", 
    "Buldhana", "Khamgaon", "Malkapur", "Chikhli", 
    "Chandrapur", "Ballarpur", "Rajura", 
    "Dhule", "Shirpur", "Sakri", 
    "Gadchiroli", "Aheri", "Desaiganj", 
    "Gondia", "Tirora", "Amgaon", 
    "Hingoli", "Kalamnuri", "Sengaon", 
    "Jalgaon", "Bhusawal", "Chalisgaon", "Yawal", 
    "Jalna", "Partur", "Ambad", 
    "Kolhapur", "Ichalkaranji", "Gadhinglaj", "Kagal", 
    "Latur", "Udgir", "Ausa", "Nilanga", 
    "Mumbai City", "Colaba", "Girgaon", "Byculla", 
    "Mumbai Suburban", "Bandra", "Andheri", "Borivali", 
    "Nagpur", "Kamptee", "Katol", "Hingna", 
    "Nanded", "Deglur", "Mukhed", "Kinwat", 
    "Nandurbar", "Shahada", "Taloda", "Navapur", 
    "Nashik", "Malegaon", "Igatpuri", "Sinnar", 
    "Osmanabad", "Tuljapur", "Omerga", "Kalamb", 
    "Palghar", "Vasai", "Dahanu", "Boisar", 
    "Parbhani", "Pathri", "Gangakhed", 
    "Pune", "Baramati", "Shirur", "Indapur", "Junnar", 
    "Raigad", "Alibaug", "Panvel", "Uran", "Karjat", 
    "Ratnagiri", "Chiplun", "Dapoli", "Sangameshwar", 
    "Sangli", "Miraj", "Tasgaon", "Jat", 
    "Satara", "Karad", "Wai", "Phaltan", 
    "Sindhudurg", "Kudal", "Sawantwadi", "Malvan", 
    "Solapur", "Pandharpur", "Mangalwedha", "Barshi", 
    "Thane", "Kalyan", "Ulhasnagar", "Bhiwandi", 
    "Wardha", "Hinganghat", "Arvi", "Seloo", 
    "Washim", "Mangrulpir", "Risod", 
    "Yavatmal", "Pandharkawada", "Pusad", "Digras",

    // Goa
    "North Goa", "Panaji", "Mapusa", "Bicholim", "Pernem", "Valpoi", "Calangute", "Candolim",
    "South Goa", "Margao", "Vasco da Gama", "Ponda", "Canacona", "Curchorem", "Colva", "Quepem",

    //Andhra Pradesh
    "Anakapalli", "Anantapur", "Bapatla", "Chittoor", "East Godavari", "Eluru", "Guntur", "Kakinada", "Krishna", 
    "Kurnool", "Nandyal", "NTR", "Palnadu", "Parvathipuram Manyam", "Prakasam", "Srikakulam", "Sri Potti Sriramulu Nellore", 
    "Sri Sathya Sai", "Tirupati", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR", "YSR Annamayya", "Nellore", "Kadapa", 
    "Amaravati", "Rajahmundry", "Bhimavaram", "Chirala", "Narasaraopet", "Srikalahasti", "Peddapuram", "Kothapeta", 
    "Tadepalligudem", "Macherla", "Addanki", "Kavali", "Dharmavaram", "Jangareddygudem", "Peddapalli", "Pedduru", 
    "Machilipatnam", "Gudivada", "Nellore", "Kakinada", "Rajampet", "Vijayawada", "Kandukur", "Chittoor", "Tadipatri",

    //Telangana
    "Adilabad", "Bhadradri Kothagudem", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", 
    "Kamareddy", "Karimnagar", "Khammam", "Kumuram Bheem Asifabad", "Mahabubabad", "Mahabubnagar", "Mancherial", "Medak", 
    "Medchal-Malkajgiri", "Mulugu", "Nagarkurnool", "Nalgonda", "Narayanpet", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", 
    "Rangareddy", "Sangareddy", "Siddipet", "Suryapet", "Vikarabad", "Wanaparthy", "Warangal Rural", "Warangal Urban", "Yadadri Bhuvanagiri",

    //Uttar Pradesh
    "Lucknow", "Barabanki", "Raebareli", "Unnao", "Hardoi", "Kanpur Nagar", "Kanpur Dehat", "Sitapur", "Fatehpur", 
    "Pratapgarh", "Ayodhya", "Sultanpur", "Shahjahanpur", "Gonda", "Ballia", "Bahraich", "Basti", "Deoria", "Mau", 
    "Azamgarh", "Chitrakoot", "Sonbhadra", "Mirzapur", "Sant Kabir Nagar", "Varanasi",

    //Delhi
    "Central Delhi", "East Delhi", "New Delhi", "North Delhi", "North East Delhi", "North West Delhi", "Shahdara", 
    "South Delhi", "South East Delhi", "South West Delhi", "West Delhi", "North West Delhi", "West Delhi", "Central Delhi",

    //Jammu and Kashmir
    "Anantnag", "Bandipora", "Baramulla", "Budgam", "Doda", "Jammu", "Kathua", "Kishtwar", "Kulgam", "Kupwara", 
    "Poonch", "Pulwama", "Rajouri", "Samba", "Shopian", "Srinagar", "Udhampur",

    //Madhya Pradesh
    "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chhindwara", 
    "Damoh", "Datia", "Dewas", "Dhar", "Dindori", "Guna", "Gwalior", "Harda", "Hoshangabad", "Indore", "Jabalpur", 
    "Jhabua", "Katni", "Khandwa", "Khargone", "Mandla", "Mandsaur", "Morena", "Narsinghpur", "Neemuch", "Panna", 
    "Rewa", "Sagar", "Satna", "Sehore", "Seoni", "Shahdol", "Shajapur", "Sheopur", "Shivpuri", "Singrauli", "Tikamgarh", 
    "Ujjain", "Umaria", "Vidisha",

    // Lakshadweep
    "Amini", "Andrott", "Bangaram", "Bitra", "Chethlat", "Kalapeni", "Kavaratti", "Kondul", "Maa", "Minicoy",
    "Suheli Par", "Teressa",

    // Manipur
    "Bishnupur", "Chandel", "Churachandpur", "Imphal East", "Imphal West", "Jiribam", "Kakching", "Kamjong", 
    "Kangpokpi", "Noney", "Pherzawl", "Senapati", "Tamenglong", "Thoubal", "Tengnoupal", "Ukhrul",

    // Arunachal Pradesh
    "Anjaw", "Changlang", "East Kameng", "East Siang", "Kra Daadi", "Kurung Kumey", "Lohit", "Longding", 
    "Lower Dibang Valley", "Lower Subansiri", "Namsai", "Papum Pare", "Siang", "Tawang", "Tirap", "Upper Dibang Valley", 
    "Upper Siang", "Upper Subansiri", "West Kameng", "West Siang",

     // Assam
    "Baksa", "Barpeta", "Bongaigaon", "Cachar", "Charaideo", "Darrang", "Dhemaji", "Dibrugarh", "Goalpara", 
    "Golaghat", "Hailakandi", "Jorhat", "Kamrup", "Kamrup Metropolitan", "Karbi Anglong", "Karimganj", "Kokrajhar", 
    "Lakhimpur", "Marigaon", "Nagaon", "Nalbari", "Sivasagar", "Sonitpur", "South Salmara-Mankachar", "Tinsukia", 
    "Udalguri", "West Karbi Anglong",

    // Odisha
    "Angul", "Bargarh", "Baudh", "Balangir", "Balasore", "Cuttack", "Deogarh", "Dhenkanal", "Gajapati", 
    "Ganjam", "Jagatsinghpur", "Jajpur", "Kalahandi", "Kandhamal", "Kendrapara", "Kendujhar", "Khurda", 
    "Koraput", "Malkangiri", "Mayurbhanj", "Nabarangpur", "Nayagarh", "Nuapada", "Puri", "Rayagada", 
    "Sambalpur", "Subarnapur", "Sundargarh",

     // Punjab
    "Amritsar", "Barnala", "Bathinda", "Faridkot", "Fatehgarh Sahib", "Firozpur", "Gurdaspur", "Hoshiarpur", 
    "Jalandhar", "Kapurthala", "Ludhiana", "Mansa", "Moga", "Muktsar", "Nawan Shahr", "Patiala", "Rupnagar", 
    "S.A.S. Nagar", "Sangrur", "Shahid Bhagat Singh Nagar", "Tarn Taran",

    // Uttarakhand
    "Almora", "Bageshwar", "Chamoli", "Champawat", "Dehradun", "Haridwar", "Nainital", "Pauri Garhwal", 
    "Pithoragarh", "Rudraprayag", "Tehri Garhwal", "Udham Singh Nagar", "Uttarkashi",

    // Chhattisgarh
    "Balod", "Baloda Bazar", "Balrampur", "Bastar", "Bemetara", "Bijapur", "Bilaspur", "Dantewada", "Dhamtari", 
    "Durg", "Gariaband", "Janjgir-Champa", "Jashpur", "Kanker", "Kabirdham", "Korba", "Koria", "Mahasamund", 
    "Mungeli", "Narayanpur", "Raigarh", "Raipur", "Rajnandgaon", "Sukma", "Surajpur", "Surguja",

    // Haryana
    "Ambala", "Bhiwani", "Charkhi Dadri", "Faridabad", "Fatehabad", "Gurugram", "Hisar", "Jhajjar", "Jind", 
    "Kaithal", "Karnal", "Kurukshetra", "Mahendragarh", "Mewat", "Palwal", "Panchkula", "Panipat", "Rewari", 
    "Sirsa", "Sonipat", "Yamunanagar",

    // Bihar
    "Araria", "Arwal", "Aurangabad", "Banka", "Begusarai", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", 
    "East Champaran", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Kaimur", "Katihar", "Khagaria", "Kishanganj", 
    "Lakhisarai", "Madhepura", "Madhubani", "Munger", "Muzaffarpur", "Nalanda", "Nawada", "Purnia", 
    "Rohtas", "Saharsa", "Samastipur", "Saran", "Sheikhpura", "Sheohar", "Sitamarhi", "Siwan", "Supaul", 
    "Vaishali", "West Champaran",

    // Sikkim
    "East Sikkim", "North Sikkim", "South Sikkim", "West Sikkim",

    // Meghalaya
    "East Garo Hills", "East Khasi Hills", "Jaintia Hills", "Ri-Bhoi", "South Garo Hills", "South West Garo Hills", 
    "West Garo Hills", "West Khasi Hills",

    // Tripura
    "Dhalai", "Khowai", "North Tripura", "Sepahijala", "South Tripura", "Unakoti", "West Tripura",

    // Mizoram
    "Aizawl", "Champhai", "Kolasib", "Lawngtlai", "Lunglei", "Mamit", "Saiha", "Serchhip",

    // Nagaland
    "Dimapur", "Kiphire", "Kohima", "Longleng", "Mokokchung", "Mon", "Peren", "Phek", "Tuensang", "Wokha", 
    "Zunheboto"

];
echo json_encode($districts);
?>