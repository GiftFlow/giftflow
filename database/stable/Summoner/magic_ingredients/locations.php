<?php


$cities = "New York,New Haven,Bridgeport,Los Angeles,Chicago,
				Hartford,Houston,Phoenix,
				Philadelphia,San Antonio,San Diego,Dallas,
				San Jose,Detroit,Jacksonville,Indianapolis,
				San Francisco,Columbus,Austin,Memphis,Fort Worth,
				Baltimore,Charlotte,El Paso,Boston,Seattle,Washington,
				Milwaukee,Denver,Louisville,Las Vegas,Nashville,
				Oklahoma City,Portland,Tucson,Albuquerque,Atlanta,
				Long Beach,Fresno,Sacramento,Mesa,Kansas City,Cleveland,
				Virginia Beach,Omaha,Miami,Oakland,Tulsa,Honolulu,Minneapolis,
				Colorado Springs,Arlington,Wichita,Raleigh,St. Louis,Santa Ana,
				Anaheim,Tampa,Cincinnati,Pittsburgh,Bakersfield,Aurora,
				Toledo,Riverside,Stockton,Corpus Christi,Newark,Anchorage,Buffalo,
				St. Paul,Lexington-Fayette,Plano,Fort Wayne,St. Petersburg,
				Glendale,Jersey City,Guilford,Henderson,Chandler,Greensboro,Scottsdale,
				Baton Rouge,Birmingham,Norfolk,Madison,New Orleans,Chesapeake,
				Orlando,Garland,Hialeah,Laredo,Chula Vista,Lubbock,Reno,Akron,
				Durham,Rochester,Modesto,Montgomery,Fremont,Shreveport,Arlington,Glendale";

$city = explode(",", $cities);

$locations = array(
	
	0 => array(
		'city' => 'New York',
		'lat' => '40.7143528',
		'lng' => '-74.0059731',
		'state' => 'NY',
		'address' => 'New York, NY, USA'
	),
	1 => array(
		'city' => ' New Haven',
		'lat' => '41.3081527',
		'lng' => '-72.9281577',
		'state' => 'CT',
		'address' => 'New Haven, CT, USA'
	),
	2 => array(
		'city' => ' Bridgeport',
		'lat' => '41.1670412',
		'lng' => '-73.2048348',
		'state' => 'CT',
		'address' => 'Bridgeport, CT, USA'
	),
	3 => array(
		'city' => ' Los Angeles',
		'lat' => '34.0522342',
		'lng' => '-118.2436849',
		'state' => 'CA',
		'address' => 'Los Angeles, CA, USA'
	),
	4 => array(
		'city' => ' Chicago',
		'lat' => '41.8781136',
		'lng' => '-87.6297982',
		'state' => 'IL',
		'address' => 'Chicago, IL, USA'
	),
	5 => array(
		'city' => ' Hartford',
		'lat' => '41.7637111',
		'lng' => '-72.6850932',
		'state' => 'CT',
		'address' => 'Hartford, CT, USA'
	),
	6 => array(
		'city' => ' Houston',
		'lat' => '29.7628844',
		'lng' => '-95.3830615',
		'state' => 'TX',
		'address' => 'Houston, TX, USA'
	),
	7 => array(
		'city' => ' Phoenix',
		'lat' => '33.4483771',
		'lng' => '-112.0740373',
		'state' => 'AZ',
		'address' => 'Phoenix, AZ, USA'
	),
	8 => array(
		'city' => ' Philadelphia',
		'lat' => '39.952335',
		'lng' => '-75.163789',
		'state' => 'PA',
		'address' => 'Philadelphia, PA, USA'
	),
	9 => array(
		'city' => ' San Antonio',
		'lat' => '29.4241219',
		'lng' => '-98.4936282',
		'state' => 'TX',
		'address' => 'San Antonio, TX, USA'
	),
	10 => array(
		'city' => ' San Diego',
		'lat' => '32.7153292',
		'lng' => '-117.1572551',
		'state' => 'CA',
		'address' => 'San Diego, CA, USA'
	),
	11 => array(
		'city' => ' San Jose',
		'lat' => '37.3393857',
		'lng' => '-121.8949555',
		'state' => 'CA',
		'address' => 'San Jose, CA, USA'
	),
	12 => array(
		'city' => ' Detroit',
		'lat' => '42.331427',
		'lng' => '-83.0457538',
		'state' => 'MI',
		'address' => 'Detroit, MI, USA'
	),
	13 => array(
		'city' => ' Jacksonville',
		'lat' => '30.3321838',
		'lng' => '-81.655651',
		'state' => 'FL',
		'address' => 'Jacksonville, FL, USA'
	),
	14 => array(
		'city' => ' Indianapolis',
		'lat' => '39.7683765',
		'lng' => '-86.1580423',
		'state' => 'IN',
		'address' => 'Indianapolis, IN, USA'
	),
	15 => array(
		'city' => ' San Francisco',
		'lat' => '37.7749295',
		'lng' => '-122.4194155',
		'state' => 'CA',
		'address' => 'San Francisco, CA, USA'
	),
	16 => array(
		'city' => ' Baltimore',
		'lat' => '39.2903848',
		'lng' => '-76.6121893',
		'state' => 'MD',
		'address' => 'Baltimore, MD, USA'
	),
	17 => array(
		'city' => ' El Paso',
		'lat' => '31.7587198',
		'lng' => '-106.4869314',
		'state' => 'TX',
		'address' => 'El Paso, TX, USA'
	),
	18 => array(
		'city' => ' Boston',
		'lat' => '42.3584308',
		'lng' => '-71.0597732',
		'state' => 'MA',
		'address' => 'Boston, MA, USA'
	),
	19 => array(
		'city' => ' Seattle',
		'lat' => '47.6062095',
		'lng' => '-122.3320708',
		'state' => 'WA',
		'address' => 'Seattle, WA, USA'
	),
	20 => array(
		'city' => ' Washington',
		'lat' => '38.8951118',
		'lng' => '-77.0363658',
		'state' => 'DC',
		'address' => 'Washington D.C., DC, USA'
	),
	21 => array(
		'city' => ' Milwaukee',
		'lat' => '43.0389025',
		'lng' => '-87.9064736',
		'state' => 'WI',
		'address' => 'Milwaukee, WI, USA'
	),
	22 => array(
		'city' => ' Denver',
		'lat' => '39.7391536',
		'lng' => '-104.9847034',
		'state' => 'CO',
		'address' => 'Denver, CO, USA'
	),
	23 => array(
		'city' => ' Las Vegas',
		'lat' => '36.114646',
		'lng' => '-115.172816',
		'state' => 'NV',
		'address' => 'Las Vegas, NV, USA'
	),
	24 => array(
		'city' => ' Oklahoma City',
		'lat' => '35.4675602',
		'lng' => '-97.5164276',
		'state' => 'OK',
		'address' => 'Oklahoma City, OK, USA'
	),
	25 => array(
		'city' => ' Portland',
		'lat' => '45.5234515',
		'lng' => '-122.6762071',
		'state' => 'OR',
		'address' => 'Portland, OR, USA'
	),
	26 => array(
		'city' => ' Tucson',
		'lat' => '32.2217429',
		'lng' => '-110.926479',
		'state' => 'AZ',
		'address' => 'Tucson, AZ, USA'
	),
	27 => array(
		'city' => ' Albuquerque',
		'lat' => '35.0844909',
		'lng' => '-106.6511367',
		'state' => 'NM',
		'address' => 'Albuquerque, NM, USA'
	),
	28 => array(
		'city' => ' Atlanta',
		'lat' => '33.7489954',
		'lng' => '-84.3879824',
		'state' => 'GA',
		'address' => 'Atlanta, GA, USA'
	),
	29 => array(
		'city' => ' Fresno',
		'lat' => '36.7477272',
		'lng' => '-119.7723661',
		'state' => 'CA',
		'address' => 'Fresno, CA, USA'
	),
	30 => array(
		'city' => ' Sacramento',
		'lat' => '38.5815719',
		'lng' => '-121.4943996',
		'state' => 'CA',
		'address' => 'Sacramento, CA, USA'
	),
	31 => array(
		'city' => ' Kansas City',
		'lat' => '39.114053',
		'lng' => '-94.6274636',
		'state' => 'KS',
		'address' => 'Kansas City, KS, USA'
	),
	32 => array(
		'city' => ' Cleveland',
		'lat' => '41.4994954',
		'lng' => '-81.6954088',
		'state' => 'OH',
		'address' => 'Cleveland, OH, USA'
	),
	33 => array(
		'city' => ' Virginia Beach',
		'lat' => '36.8529263',
		'lng' => '-75.977985',
		'state' => 'VA',
		'address' => 'Virginia Beach, VA, USA'
	),
	34 => array(
		'city' => ' Omaha',
		'lat' => '41.254006',
		'lng' => '-95.999258',
		'state' => 'NE',
		'address' => 'Omaha, NE, USA'
	),
	35 => array(
		'city' => ' Miami',
		'lat' => '25.7889689',
		'lng' => '-80.2264393',
		'state' => 'FL',
		'address' => 'Miami, FL, USA'
	),
	36 => array(
		'city' => ' Oakland',
		'lat' => '37.8043637',
		'lng' => '-122.2711137',
		'state' => 'CA',
		'address' => 'Oakland, CA, USA'
	),
	37 => array(
		'city' => ' Tulsa',
		'lat' => '36.1539816',
		'lng' => '-95.992775',
		'state' => 'OK',
		'address' => 'Tulsa, OK, USA'
	),
	38 => array(
		'city' => ' Honolulu',
		'lat' => '21.3069444',
		'lng' => '-157.8583333',
		'state' => 'HI',
		'address' => 'Honolulu, HI, USA'
	),
	39 => array(
		'city' => ' Minneapolis',
		'lat' => '44.9799654',
		'lng' => '-93.2638361',
		'state' => 'MN',
		'address' => 'Minneapolis, MN, USA'
	),
	40 => array(
		'city' => ' Colorado Springs',
		'lat' => '38.8338816',
		'lng' => '-104.8213634',
		'state' => 'CO',
		'address' => 'Colorado Springs, CO, USA'
	),
	41 => array(
		'city' => ' Arlington',
		'lat' => '32.735687',
		'lng' => '-97.1080656',
		'state' => 'TX',
		'address' => 'Arlington, TX, USA'
	),
	42 => array(
		'city' => ' Wichita',
		'lat' => '37.6922361',
		'lng' => '-97.3375448',
		'state' => 'KS',
		'address' => 'Wichita, KS, USA'
	),
	43 => array(
		'city' => ' Raleigh',
		'lat' => '35.772096',
		'lng' => '-78.6386145',
		'state' => 'NC',
		'address' => 'Raleigh, NC, USA'
	),
	44 => array(
		'city' => ' St. Louis',
		'lat' => '38.646991',
		'lng' => '-90.224967',
		'state' => 'MO',
		'address' => 'St Louis, MO, USA'
	),
	45 => array(
		'city' => ' Tampa',
		'lat' => '27.949436',
		'lng' => '-82.4651441',
		'state' => 'FL',
		'address' => 'Tampa, FL, USA'
	),
	46 => array(
		'city' => ' Cincinnati',
		'lat' => '39.1031182',
		'lng' => '-84.5120196',
		'state' => 'OH',
		'address' => 'Cincinnati, OH, USA'
	),
	47 => array(
		'city' => ' Pittsburgh',
		'lat' => '40.4406248',
		'lng' => '-79.9958864',
		'state' => 'PA',
		'address' => 'Pittsburgh, PA, USA'
	),
	48 => array(
		'city' => ' Bakersfield',
		'lat' => '35.3732921',
		'lng' => '-119.0187125',
		'state' => 'CA',
		'address' => 'Bakersfield, CA, USA'
	),
	49 => array(
		'city' => ' Aurora',
		'lat' => '41.7605849',
		'lng' => '-88.3200715',
		'state' => 'IL',
		'address' => 'Aurora, IL, USA'
	),
	50 => array(
		'city' => ' Toledo',
		'lat' => '41.6639383',
		'lng' => '-83.555212',
		'state' => 'OH',
		'address' => 'Toledo, OH, USA'
	),
	51 => array(
		'city' => ' Riverside',
		'lat' => '33.9533487',
		'lng' => '-117.3961564',
		'state' => 'CA',
		'address' => 'Riverside, CA, USA'
	),
	52 => array(
		'city' => ' Stockton',
		'lat' => '37.9577016',
		'lng' => '-121.2907796',
		'state' => 'CA',
		'address' => 'Stockton, CA, USA'
	),
	53 => array(
		'city' => ' Corpus Christi',
		'lat' => '27.8005828',
		'lng' => '-97.396381',
		'state' => 'TX',
		'address' => 'Corpus Christi, TX, USA'
	),
	54 => array(
		'city' => ' Newark',
		'lat' => '40.735657',
		'lng' => '-74.1723667',
		'state' => 'NJ',
		'address' => 'Newark, NJ, USA'
	),
	55 => array(
		'city' => ' Anchorage',
		'lat' => '61.2180556',
		'lng' => '-149.9002778',
		'state' => 'AK',
		'address' => 'Anchorage, AK, USA'
	),
	56 => array(
		'city' => ' Buffalo',
		'lat' => '42.8864468',
		'lng' => '-78.8783689',
		'state' => 'NY',
		'address' => 'Buffalo, NY, USA'
	),
	57 => array(
		'city' => ' St. Paul',
		'lat' => '44.9541667',
		'lng' => '-93.1138889',
		'state' => 'MN',
		'address' => 'St Paul, MN, USA'
	),
	58 => array(
		'city' => ' Plano',
		'lat' => '33.0198431',
		'lng' => '-96.6988856',
		'state' => 'TX',
		'address' => 'Plano, TX, USA'
	),
	59 => array(
		'city' => ' Glendale',
		'lat' => '33.5386523',
		'lng' => '-112.1859866',
		'state' => 'AZ',
		'address' => 'Glendale, AZ, USA'
	),
	60 => array(
		'city' => ' Jersey City',
		'lat' => '40.7281575',
		'lng' => '-74.0776417',
		'state' => 'NJ',
		'address' => 'Jersey City, NJ, USA'
	),
	61 => array(
		'city' => 'Guilford',
		'lat' => '41.3081527',
		'lng' => '-72.9281577',
		'state' => 'CT',
		'address' => 'Guilford, CT, USA'
	),
	62 => array(
		'city' => ' Henderson',
		'lat' => '36.033669',
		'lng' => '-115.002364',
		'state' => 'NV',
		'address' => 'Henderson, NV, USA'
	),
	63 => array(
		'city' => ' Chandler',
		'lat' => '33.3061605',
		'lng' => '-111.8412502',
		'state' => 'AZ',
		'address' => 'Chandler, AZ, USA'
	),
	64 => array(
		'city' => ' Greensboro',
		'lat' => '36.0726354',
		'lng' => '-79.7919754',
		'state' => 'NC',
		'address' => 'Greensboro, NC, USA'
	),
	65 => array(
		'city' => ' Scottsdale',
		'lat' => '33.4941704',
		'lng' => '-111.9260519',
		'state' => 'AZ',
		'address' => 'Scottsdale, AZ, USA'
	),
	66 => array(
		'city' => ' Baton Rouge',
		'lat' => '30.4582829',
		'lng' => '-91.1403196',
		'state' => 'LA',
		'address' => 'Baton Rouge, LA, USA'
	),
	67 => array(
		'city' => ' Birmingham',
		'lat' => '33.5206608',
		'lng' => '-86.80249',
		'state' => 'AL',
		'address' => 'Birmingham, AL, USA'
	),
	68 => array(
		'city' => ' Norfolk',
		'lat' => '36.8507689',
		'lng' => '-76.2858726',
		'state' => 'VA',
		'address' => 'Norfolk, VA, USA'
	),
	69 => array(
		'city' => ' Madison',
		'lat' => '43.0730517',
		'lng' => '-89.4012302',
		'state' => 'WI',
		'address' => 'Madison, WI, USA'
	),
	70 => array(
		'city' => ' New Orleans',
		'lat' => '29.9647222',
		'lng' => '-90.0705556',
		'state' => 'LA',
		'address' => 'New Orleans, LA, USA'
	),
	71 => array(
		'city' => ' Chesapeake',
		'lat' => '36.7682088',
		'lng' => '-76.2874927',
		'state' => 'VA',
		'address' => 'Chesapeake, VA, USA'
	),
	72 => array(
		'city' => ' Orlando',
		'lat' => '28.5383355',
		'lng' => '-81.3792365',
		'state' => 'FL',
		'address' => 'Orlando, FL, USA'
	),
	73 => array(
		'city' => ' Garland',
		'lat' => '32.912624',
		'lng' => '-96.6388833',
		'state' => 'TX',
		'address' => 'Garland, TX, USA'
	),
	74 => array(
		'city' => ' Hialeah',
		'lat' => '25.8575963',
		'lng' => '-80.2781057',
		'state' => 'FL',
		'address' => 'Hialeah, FL, USA'
	),
	75 => array(
		'city' => ' Laredo',
		'lat' => '27.506407',
		'lng' => '-99.5075421',
		'state' => 'TX',
		'address' => 'Laredo, TX, USA'
	),
	76 => array(
		'city' => ' Chula Vista',
		'lat' => '32.6400541',
		'lng' => '-117.0841955',
		'state' => 'CA',
		'address' => 'Chula Vista, CA, USA'
	),
	77 => array(
		'city' => ' Lubbock',
		'lat' => '33.5778631',
		'lng' => '-101.8551665',
		'state' => 'TX',
		'address' => 'Lubbock, TX, USA'
	),
	78 => array(
		'city' => ' Reno',
		'lat' => '39.5296329',
		'lng' => '-119.8138027',
		'state' => 'NV',
		'address' => 'Reno, NV, USA'
	),
	79 => array(
		'city' => ' Akron',
		'lat' => '41.0814447',
		'lng' => '-81.5190053',
		'state' => 'OH',
		'address' => 'Akron, OH, USA'
	),
	80 => array(
		'city' => ' Durham',
		'lat' => '35.9940329',
		'lng' => '-78.898619',
		'state' => 'NC',
		'address' => 'Durham, NC, USA'
	),
	81 => array(
		'city' => ' Rochester',
		'lat' => '43.16103',
		'lng' => '-77.6109219',
		'state' => 'NY',
		'address' => 'Rochester, NY, USA'
	),
	82 => array(
		'city' => ' Modesto',
		'lat' => '37.6390972',
		'lng' => '-120.9968782',
		'state' => 'CA',
		'address' => 'Modesto, CA, USA'
	),
	83 => array(
		'city' => ' Montgomery',
		'lat' => '32.3668052',
		'lng' => '-86.2999689',
		'state' => 'AL',
		'address' => 'Montgomery, AL, USA'
	),
	84 => array(
		'city' => ' Fremont',
		'lat' => '37.5482697',
		'lng' => '-121.9885719',
		'state' => 'CA',
		'address' => 'Fremont, CA, USA'
	),
	85 => array(
		'city' => ' Shreveport',
		'lat' => '32.5251516',
		'lng' => '-93.7501789',
		'state' => 'LA',
		'address' => 'Shreveport, LA, USA'
	),
	86 => array(
		'city' => ' Arlington',
		'lat' => '32.735687',
		'lng' => '-97.1080656',
		'state' => 'TX',
		'address' => 'Arlington, TX, USA'
	),
	87 => array(
		'city' => ' Glendale',
		'lat' => '33.5386523',
		'lng' => '-112.1859866',
		'state' => 'Maricopa',
		'address' => 'Glendale, AZ, USA'
	)
);

?>