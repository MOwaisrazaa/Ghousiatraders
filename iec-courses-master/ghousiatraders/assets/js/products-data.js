/* ==========================================================================
   Ghousia Traders - Master Products Database
   ========================================================================== */

const PRODUCTS_DATABASE = {
    // ----------------------------------------------------------------------
    // B/O CARS
    // ----------------------------------------------------------------------
    'mercedes-amg': {
        id: 'mercedes-amg',
        name: 'Mercedes B/O Car (AMG)',
        category: 'B/O Cars',
        categoryUrl: 'cars.html',
        price: 'PKR 29,999',
        priceNum: 29999,
        oldPrice: 'PKR 34,999',
        saveText: 'Save PKR 5,000',
        rating: 4.8,
        reviewsCount: 128,
        badge: 'Best Seller',
        sku: 'BOC-AMG-WHT',
        stockStatus: 'In Stock',
        shortDesc: 'Premium licensed Mercedes AMG ride-on car for kids with remote control, LED lights, music player and rechargeable battery for a real driving experience.',
        images: [
            'assets/mercedes_amg_front.png',
            'assets/mercedes_amg_side.png',
            'assets/mercedes_amg_dashboard.png',
            'assets/mercedes_amg_wheel.png'
        ],
        variations: {
            color: { label: 'Color', default: 'White', options: [
                { name: 'White', class: 'swatch-white' },
                { name: 'Black', class: 'swatch-black' },
                { name: 'Red', class: 'swatch-red' }
            ]},
            age: { label: 'Age Group', default: '2 - 4 Years', options: ['2 - 4 Years', '4 - 6 Years', '6+ Years'] },
            battery: { label: 'Battery Type', default: '6V 4.5Ah', options: ['6V 4.5Ah', '12V 7Ah'] }
        },
        features: [
            { icon: 'battery-charging', title: 'Rechargeable Battery', desc: 'Long Lasting Power' },
            { icon: 'gamepad-2', title: 'Remote Control', desc: 'Parental Control' },
            { icon: 'sparkles', title: 'LED Lights', desc: 'Bright & Stylish' },
            { icon: 'smile', title: 'Safe & Child Friendly', desc: 'EN71 Certified' },
            { icon: 'music', title: 'Music Player', desc: 'Built-in Fun' }
        ],
        descriptionBullets: [
            'Licensed Mercedes AMG design with realistic looks',
            'Dual control: Kids can drive or parents can control remotely',
            'Bright LED headlights and taillights',
            'Built-in music player with USB & MP3 support',
            'Smooth start function for safe acceleration',
            'Durable wheels for indoor & outdoor use',
            'Comfortable seat with safety belt',
            'Rechargeable battery for long playtime'
        ],
        specifications: [
            { label: 'Battery', value: '12V 7Ah Rechargeable' },
            { label: 'Charging Time', value: '8 – 10 Hours' },
            { label: 'Run Time', value: '1 – 1.5 Hours' },
            { label: 'Seat Capacity', value: '1 Seater' },
            { label: 'Material', value: 'High Quality Plastic' },
            { label: 'Recommended Age', value: '2 – 6 Years' },
            { label: 'Max Weight Capacity', value: '30 kg' },
            { label: 'Speed', value: '3 – 5 km/h' },
            { label: 'Product Dimensions', value: '110 × 65 × 50 cm' },
            { label: 'Package Dimensions', value: '112 × 58 × 33 cm' }
        ],
        relatedIds: ['jeep-wrangler', 'land-cruiser', 'range-rover', 'audi-r8']
    },

    'jeep-wrangler': {
        id: 'jeep-wrangler',
        name: 'Jeep Wrangler 4WD Car (JM09)',
        category: 'B/O Cars',
        categoryUrl: 'cars.html',
        price: 'PKR 34,999',
        priceNum: 34999,
        oldPrice: 'PKR 39,999',
        saveText: 'Save PKR 5,000',
        rating: 4.9,
        reviewsCount: 94,
        badge: 'Best Seller',
        sku: 'BOC-JEP-RED',
        stockStatus: 'In Stock',
        shortDesc: 'Heavy duty 4-wheel drive Jeep Wrangler electric ride-on for off-road fun, equipped with 4 motors, leather seat, and parental remote.',
        images: [
            'assets/toy_jeep.png',
            'assets/black_suv.png',
            'assets/ride_on_toys.png',
            'assets/sports_car_yellow.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Red', options: [
                { name: 'Red', class: 'swatch-red' },
                { name: 'Black', class: 'swatch-black' },
                { name: 'White', class: 'swatch-white' }
            ]},
            age: { label: 'Age Group', default: '3 - 8 Years', options: ['3 - 5 Years', '5 - 8 Years', '8+ Years'] },
            battery: { label: 'Battery Type', default: '12V 7Ah', options: ['12V 7Ah', '12V 10Ah (Dual Motor)'] }
        },
        features: [
            { icon: 'shield-check', title: '4-Wheel Drive', desc: 'Powerful 4 Motors' },
            { icon: 'gamepad-2', title: 'Parental Remote', desc: '2.4G Bluetooth' },
            { icon: 'sparkles', title: 'Off-Road Suspension', desc: 'All-Terrain Wheels' },
            { icon: 'smile', title: 'High Door Locks', desc: 'Maximum Safety' },
            { icon: 'music', title: 'FM Radio & USB', desc: 'Bluetooth Audio' }
        ],
        descriptionBullets: [
            'Authentic 4WD Jeep Wrangler design with high ground clearance',
            'Powerful 4-motor drive system for all terrains',
            '2.4GHz Bluetooth remote control for parent override',
            'Leather seat with 5-point safety harness',
            'Spring suspension on all 4 wheels for smooth ride',
            'FM Radio, USB slot, Bluetooth audio connectivity'
        ],
        specifications: [
            { label: 'Battery', value: '12V 10Ah Heavy Duty' },
            { label: 'Motors', value: '4 × 35W Motors' },
            { label: 'Charging Time', value: '8 – 10 Hours' },
            { label: 'Run Time', value: '1.5 – 2 Hours' },
            { label: 'Max Weight Capacity', value: '40 kg' },
            { label: 'Speed', value: '4 – 7 km/h' },
            { label: 'Dimensions', value: '125 × 75 × 70 cm' }
        ],
        relatedIds: ['mercedes-amg', 'land-cruiser', 'range-rover', 'audi-r8']
    },

    'land-cruiser': {
        id: 'land-cruiser',
        name: 'Toyota Land Cruiser B/O Car',
        category: 'B/O Cars',
        categoryUrl: 'cars.html',
        price: 'PKR 32,999',
        priceNum: 32999,
        oldPrice: 'PKR 36,999',
        saveText: 'Save PKR 4,000',
        rating: 4.7,
        reviewsCount: 62,
        badge: 'Hot',
        sku: 'BOC-TLC-BLK',
        stockStatus: 'In Stock',
        shortDesc: 'Spacious SUV Toyota Land Cruiser ride-on car with dual doors, LED lights, working horn, and remote control.',
        images: [
            'assets/black_suv.png',
            'assets/toy_jeep.png',
            'assets/ride_on_toys.png',
            'assets/sports_car_yellow.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Black', options: [
                { name: 'Black', class: 'swatch-black' },
                { name: 'White', class: 'swatch-white' }
            ]},
            age: { label: 'Age Group', default: '3 - 6 Years', options: ['2 - 4 Years', '3 - 6 Years'] },
            battery: { label: 'Battery Type', default: '12V 7Ah', options: ['12V 7Ah'] }
        },
        features: [
            { icon: 'battery-charging', title: '12V Battery', desc: 'Rechargeable' },
            { icon: 'gamepad-2', title: 'Remote Control', desc: 'Parent Control' },
            { icon: 'sparkles', title: 'LED Headlights', desc: 'Real SUV Design' },
            { icon: 'smile', title: 'Double Doors', desc: 'Easy Entry' },
            { icon: 'music', title: 'Audio System', desc: 'MP3 & Horn' }
        ],
        descriptionBullets: [
            'Licensed Land Cruiser style SUV body with chrome accents',
            'Opening doors with safety lock system',
            'Dashboard battery indicator display',
            'Shock absorption suspension for comfortable ride'
        ],
        specifications: [
            { label: 'Battery', value: '12V 7Ah' },
            { label: 'Run Time', value: '1 – 1.5 Hours' },
            { label: 'Recommended Age', value: '2 – 6 Years' },
            { label: 'Max Capacity', value: '35 kg' }
        ],
        relatedIds: ['mercedes-amg', 'jeep-wrangler', 'range-rover', 'audi-r8']
    },

    'range-rover': {
        id: 'range-rover',
        name: 'Range Rover B/O Car',
        category: 'B/O Cars',
        categoryUrl: 'cars.html',
        price: 'PKR 36,999',
        priceNum: 36999,
        oldPrice: 'PKR 41,999',
        saveText: 'Save PKR 5,000',
        rating: 4.9,
        reviewsCount: 51,
        badge: 'Luxury',
        sku: 'BOC-RRV-BLK',
        stockStatus: 'In Stock',
        shortDesc: 'Luxury Edition Range Rover battery operated ride-on car with touchscreen dashboard, leather seats, and metallic finish.',
        images: [
            'assets/black_suv.png',
            'assets/ride_on_toys.png',
            'assets/toy_jeep.png',
            'assets/sports_car_yellow.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Black', options: [
                { name: 'Black', class: 'swatch-black' },
                { name: 'White', class: 'swatch-white' }
            ]},
            age: { label: 'Age Group', default: '3 - 7 Years', options: ['3 - 5 Years', '5 - 7 Years'] }
        },
        features: [
            { icon: 'sparkles', title: 'Luxury Finish', desc: 'Metallic Paint' },
            { icon: 'gamepad-2', title: '2.4G Remote', desc: 'Full Control' },
            { icon: 'music', title: 'MP4 Screen', desc: 'Multimedia' },
            { icon: 'smile', title: 'Leather Seats', desc: 'Soft Comfort' }
        ],
        descriptionBullets: [
            'Luxury Range Rover SUV design',
            'Soft EVA rubber tires for quiet smooth ride',
            'Built-in MP4 Video Player screen'
        ],
        specifications: [
            { label: 'Battery', value: '12V 10Ah' },
            { label: 'Seat', value: 'Soft Padded Leather' }
        ],
        relatedIds: ['mercedes-amg', 'land-cruiser', 'jeep-wrangler', 'audi-r8']
    },

    'audi-r8': {
        id: 'audi-r8',
        name: 'Audi SQ5 B/O Car',
        category: 'B/O Cars',
        categoryUrl: 'cars.html',
        price: 'PKR 29,999',
        priceNum: 29999,
        oldPrice: 'PKR 35,999',
        saveText: 'Save PKR 6,000',
        rating: 4.8,
        reviewsCount: 78,
        badge: 'New',
        sku: 'BOC-AUD-YEL',
        stockStatus: 'In Stock',
        shortDesc: 'Sleek yellow Audi sports ride-on car with low-profile sports design, racing steering wheel, and LED grill lights.',
        images: [
            'assets/sports_car_yellow.png',
            'assets/ride_on_toys.png',
            'assets/mercedes_amg_front.png',
            'assets/black_suv.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Yellow', options: [
                { name: 'Yellow', class: 'swatch-white' },
                { name: 'Red', class: 'swatch-red' }
            ]}
        },
        features: [
            { icon: 'sparkles', title: 'Sports Design', desc: 'Low Aerodynamic' },
            { icon: 'gamepad-2', title: 'Parent Remote', desc: 'Safety First' },
            { icon: 'music', title: 'USB & AUX', desc: 'Music Controls' }
        ],
        descriptionBullets: [
            'Official Audi sports styling',
            'Steering wheel with horn and sound buttons',
            'Working LED headlights'
        ],
        specifications: [
            { label: 'Battery', value: '12V 7Ah' },
            { label: 'Speed', value: '3 – 5 km/h' }
        ],
        relatedIds: ['mercedes-amg', 'jeep-wrangler', 'sports-bike', 'adventure-bike']
    },

    // ----------------------------------------------------------------------
    // B/O BIKES
    // ----------------------------------------------------------------------
    'sports-bike': {
        id: 'sports-bike',
        name: 'Sports B/O Bike (Rechargeable)',
        category: 'B/O Bikes',
        categoryUrl: 'bikes.html',
        price: 'PKR 24,999',
        priceNum: 24999,
        oldPrice: 'PKR 28,999',
        saveText: 'Save PKR 4,000',
        rating: 4.8,
        reviewsCount: 86,
        badge: 'Best Seller',
        sku: 'BOB-SPT-BLU',
        stockStatus: 'In Stock',
        shortDesc: 'Exciting kids electric sports superbike with training wheels, hand throttle acceleration, LED headlight, and music engine sounds.',
        images: [
            'assets/sport_bike.png',
            'assets/blue_adventure_bike.png',
            'assets/vespa_scooter.png',
            'assets/ride_on_toys.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Red', options: [
                { name: 'Red', class: 'swatch-red' },
                { name: 'Blue', class: 'swatch-white' },
                { name: 'Black', class: 'swatch-black' }
            ]},
            age: { label: 'Age Group', default: '3 - 6 Years', options: ['2 - 4 Years', '3 - 6 Years', '6+ Years'] }
        },
        features: [
            { icon: 'battery-charging', title: 'Rechargeable 12V', desc: 'Powerful Motor' },
            { icon: 'sparkles', title: 'LED Wheel Lights', desc: 'Glowing Rim' },
            { icon: 'smile', title: 'Training Wheels', desc: 'Stabilizer Balance' },
            { icon: 'music', title: 'Engine Sound', desc: 'Horn & Music' },
            { icon: 'shield-check', title: 'Hand Throttle', desc: 'Realistic Twist' }
        ],
        descriptionBullets: [
            'Futuristic aerodynamic sports bike design',
            'Hand twist throttle for authentic motorcycle experience',
            'Detachable auxiliary training wheels for safety',
            'LED light-up wheels and front headlight'
        ],
        specifications: [
            { label: 'Battery', value: '12V 4.5Ah' },
            { label: 'Motor', value: 'Dual 380 Motors' },
            { label: 'Max Load', value: '35 kg' }
        ],
        relatedIds: ['adventure-bike', 'vespa-scooter', 'mercedes-amg', 'jeep-wrangler']
    },

    'adventure-bike': {
        id: 'adventure-bike',
        name: 'Adventure Trail B/O Bike',
        category: 'B/O Bikes',
        categoryUrl: 'bikes.html',
        price: 'PKR 32,999',
        priceNum: 32999,
        oldPrice: 'PKR 37,999',
        saveText: 'Save PKR 5,000',
        rating: 4.9,
        reviewsCount: 43,
        badge: 'Hot',
        sku: 'BOB-ADV-BLK',
        stockStatus: 'In Stock',
        shortDesc: 'Heavy-duty touring adventure motorcycle for kids with dual side storage boxes, high wind visor, and key start.',
        images: [
            'assets/blue_adventure_bike.png',
            'assets/sport_bike.png',
            'assets/vespa_scooter.png',
            'assets/ride_on_toys.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Blue', options: [
                { name: 'Blue', class: 'swatch-white' },
                { name: 'Black', class: 'swatch-black' }
            ]}
        },
        features: [
            { icon: 'battery-charging', title: '12V Battery', desc: 'Long Distance' },
            { icon: 'sparkles', title: 'Dual Side Boxes', desc: 'Storage Containers' },
            { icon: 'music', title: 'Bluetooth Audio', desc: 'Built-in Speaker' },
            { icon: 'smile', title: 'Sturdy Steel Frame', desc: 'Durable Build' }
        ],
        descriptionBullets: [
            'Touring adventure style with dual side panniers',
            'Key ignition with realistic start sound',
            'Foot pedal brake for safe quick stopping'
        ],
        specifications: [
            { label: 'Battery', value: '12V 7Ah' },
            { label: 'Recommended Age', value: '4 – 8 Years' }
        ],
        relatedIds: ['sports-bike', 'vespa-scooter', 'mercedes-amg', 'audi-r8']
    },

    'vespa-scooter': {
        id: 'vespa-scooter',
        name: 'Vespa Vintage Scooter (6V)',
        category: 'B/O Bikes',
        categoryUrl: 'bikes.html',
        price: 'PKR 19,999',
        priceNum: 19999,
        oldPrice: 'PKR 22,999',
        saveText: 'Save PKR 3,000',
        rating: 4.8,
        reviewsCount: 77,
        badge: 'Classic',
        sku: 'BOB-VES-MNT',
        stockStatus: 'In Stock',
        shortDesc: 'Classic Italian retro style Vespa electric scooter with gentle acceleration, foot pedal, chrome mirrors, and backrest.',
        images: [
            'assets/vespa_scooter.png',
            'assets/blue_adventure_bike.png',
            'assets/sport_bike.png',
            'assets/ride_on_toys.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Mint Green', options: [
                { name: 'Mint Green', class: 'swatch-white' },
                { name: 'Pink', class: 'swatch-red' }
            ]}
        },
        features: [
            { icon: 'sparkles', title: 'Retro Italian Design', desc: 'Classic Look' },
            { icon: 'smile', title: 'Comfortable Backrest', desc: 'Safe Support' },
            { icon: 'battery-charging', title: '6V Safe Battery', desc: 'Gentle Speed' }
        ],
        descriptionBullets: [
            'Charming vintage Vespa scooter styling',
            'Soft start pedal control ideal for toddlers'
        ],
        specifications: [
            { label: 'Battery', value: '6V 4.5Ah' },
            { label: 'Max Capacity', value: '25 kg' }
        ],
        relatedIds: ['sports-bike', 'adventure-bike', 'mercedes-amg', 'baby-lotion']
    },

    // ----------------------------------------------------------------------
    // BABY CARE PRODUCTS
    // ----------------------------------------------------------------------
    'baby-lotion': {
        id: 'baby-lotion',
        name: "Johnson's Baby Lotion 500ml",
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 1,250',
        priceNum: 1250,
        oldPrice: 'PKR 1,450',
        saveText: 'Save PKR 200',
        rating: 4.8,
        reviewsCount: 68,
        badge: 'Best Seller',
        sku: 'BC-LOT-500',
        stockStatus: 'In Stock',
        shortDesc: 'Deeply hydrating 24-hour moisture baby lotion infused with organic shea butter and natural almond oil for baby soft skin.',
        images: [
            'assets/baby_lotion.png',
            'assets/baby_products.png',
            'assets/baby_oil.png',
            'assets/diaper_cream.png'
        ],
        variations: {
            volume: { label: 'Volume', default: '500ml', options: ['250ml', '500ml Family Size'] }
        },
        features: [
            { icon: 'shield-check', title: '24H Hydration', desc: 'Deep Moisture' },
            { icon: 'sparkles', title: 'Shea Butter', desc: 'Natural Barrier' },
            { icon: 'smile', title: 'Pediatrician Approved', desc: 'Safe for Newborns' }
        ],
        descriptionBullets: [
            'Locks in skin moisture for 24 hours',
            'Non-greasy fast absorbing texture',
            'Mild soothing fragrance'
        ],
        specifications: [
            { label: 'Volume', value: '500 ml' },
            { label: 'Key Ingredients', value: 'Shea Butter, Sweet Almond Oil' }
        ],
        relatedIds: ['baby-wipes', 'baby-shampoo', 'sippy-cup', 'diaper-cream']
    },

    'baby-wipes': {
        id: 'baby-wipes',
        name: 'Baby Wipes 80 Pcs',
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 450',
        priceNum: 450,
        oldPrice: 'PKR 550',
        saveText: 'Save PKR 100',
        rating: 4.9,
        reviewsCount: 195,
        badge: 'Best Seller',
        sku: 'BC-WIP-80P',
        stockStatus: 'In Stock',
        shortDesc: '99% pure water wipes with thick honeycomb cotton cloth for extra soft and soothing skin cleaning.',
        images: [
            'assets/baby_wipes.png',
            'assets/baby_lotion.png',
            'assets/baby_products.png',
            'assets/baby_powder.png'
        ],
        variations: {
            pack: { label: 'Pack Count', default: 'Single Pack (80 Wipes)', options: ['Single Pack (80 Wipes)', 'Value Bundle (3 Pack)'] }
        },
        features: [
            { icon: 'shield-check', title: '99% Pure Water', desc: 'Ultra Clean' },
            { icon: 'sparkles', title: 'Thick Cloth', desc: 'Soft Honeycomb' }
        ],
        descriptionBullets: [
            'Alcohol-free, fragrance-free, unscented water wipes',
            'Thick embossed fabric prevents tearing during diaper changes'
        ],
        specifications: [
            { label: 'Quantity', value: '80 Wipes per Pack' }
        ],
        relatedIds: ['baby-lotion', 'baby-shampoo', 'diaper-cream', 'sippy-cup']
    },

    'sippy-cup': {
        id: 'sippy-cup',
        name: 'Premium Sippy Cup 260ml',
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 750',
        priceNum: 750,
        oldPrice: 'PKR 890',
        saveText: 'Save PKR 140',
        rating: 4.7,
        reviewsCount: 41,
        badge: 'New Arrival',
        sku: 'BC-CUP-260',
        stockStatus: 'In Stock',
        shortDesc: 'Spill-proof ergonomic trainer sippy cup with soft silicone spout and easy-grip handles for toddlers.',
        images: [
            'assets/sippy_cup.png',
            'assets/feeding_bottle_set.png',
            'assets/baby_products.png',
            'assets/baby_lotion.png'
        ],
        variations: {
            color: { label: 'Color', default: 'Blue', options: [
                { name: 'Blue', class: 'swatch-white' },
                { name: 'Pink', class: 'swatch-red' }
            ]}
        },
        features: [
            { icon: 'shield-check', title: '100% BPA Free', desc: 'Food Grade Silicone' },
            { icon: 'smile', title: 'Spill Proof', desc: 'Leak-Free Design' }
        ],
        descriptionBullets: [
            'Soft silicone spout gentle on teething gums',
            'Dual ergonomic side handles for independent drinking'
        ],
        specifications: [
            { label: 'Capacity', value: '260 ml' }
        ],
        relatedIds: ['baby-lotion', 'baby-wipes', 'feeding-bottle-set', 'baby-shampoo']
    },

    'baby-shampoo': {
        id: 'baby-shampoo',
        name: "Johnson's Baby Shampoo 500ml",
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 1,150',
        priceNum: 1150,
        oldPrice: 'PKR 1,299',
        saveText: 'Save PKR 149',
        rating: 4.9,
        reviewsCount: 84,
        badge: 'Tear Free',
        sku: 'BC-SHM-500',
        stockStatus: 'In Stock',
        shortDesc: 'Ultra-gentle tear-free hair wash enriched with chamomile flower extract to cleanse delicate hair and scalp smoothly.',
        images: [
            'assets/baby_shampoo.png',
            'assets/baby_lotion.png',
            'assets/baby_products.png',
            'assets/baby_soap.png'
        ],
        variations: {
            volume: { label: 'Volume', default: '500ml', options: ['250ml', '500ml Pump'] }
        },
        features: [
            { icon: 'smile', title: 'Tear-Free', desc: 'Soft on Eyes' },
            { icon: 'sparkles', title: 'Chamomile Extract', desc: 'Scalp Care' }
        ],
        descriptionBullets: [
            'Formulated to prevent eye irritation during bath time',
            'Leaves hair soft, shiny, and easy to comb'
        ],
        specifications: [
            { label: 'Volume', value: '500 ml' }
        ],
        relatedIds: ['baby-lotion', 'baby-wipes', 'baby-powder', 'diaper-cream']
    },

    'baby-powder': {
        id: 'baby-powder',
        name: "Johnson's Baby Powder 400g",
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 950',
        priceNum: 950,
        oldPrice: 'PKR 1,099',
        saveText: 'Save PKR 149',
        rating: 4.8,
        reviewsCount: 52,
        badge: 'Classic',
        sku: 'BC-PWD-400',
        stockStatus: 'In Stock',
        shortDesc: 'Soft & fresh gentle baby powder that absorbs excess moisture and keeps skin dry and comfortably soft.',
        images: [
            'assets/baby_powder.png',
            'assets/baby_lotion.png',
            'assets/baby_products.png',
            'assets/baby_wipes.png'
        ],
        variations: {
            weight: { label: 'Weight', default: '400g', options: ['200g', '400g'] }
        },
        features: [
            { icon: 'sparkles', title: 'Absorbs Moisture', desc: 'Keeps Skin Dry' }
        ],
        descriptionBullets: [
            'Dermatologically tested mild powder',
            'Soothes skin and prevents friction chafing'
        ],
        specifications: [
            { label: 'Weight', value: '400 grams' }
        ],
        relatedIds: ['baby-lotion', 'baby-wipes', 'baby-shampoo', 'diaper-cream']
    },

    'feeding-bottle-set': {
        id: 'feeding-bottle-set',
        name: 'Feeding Bottle Set 3 Pieces',
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 1,650',
        priceNum: 1650,
        oldPrice: 'PKR 1,899',
        saveText: 'Save PKR 249',
        rating: 4.8,
        reviewsCount: 38,
        badge: 'Best Seller',
        sku: 'BC-BOT-3PCS',
        stockStatus: 'In Stock',
        shortDesc: 'Anti-colic anti-gas feeding bottle set with natural latch nipples, clear measurement markings, and hygienic protective caps.',
        images: [
            'assets/feeding_bottle_set.png',
            'assets/sippy_cup.png',
            'assets/baby_products.png',
            'assets/baby_lotion.png'
        ],
        variations: {
            pack: { label: 'Set Includes', default: '3 Bottles (150ml, 250ml, 330ml)', options: ['3 Bottles (150ml, 250ml, 330ml)'] }
        },
        features: [
            { icon: 'shield-check', title: 'Anti-Colic Valve', desc: 'Reduces Gas & Reflux' },
            { icon: 'smile', title: 'Natural Latch', desc: 'Soft Breast-Like Nipple' }
        ],
        descriptionBullets: [
            'High quality BPA-free polypropylene bottles',
            'Wide neck design for quick easy cleaning'
        ],
        specifications: [
            { label: 'Set Quantity', value: '3 Bottles' }
        ],
        relatedIds: ['sippy-cup', 'baby-lotion', 'baby-wipes', 'baby-shampoo']
    },

    'diaper-cream': {
        id: 'diaper-cream',
        name: 'Diaper Rash Cream 100g',
        category: 'Baby Care',
        categoryUrl: 'babycare.html',
        price: 'PKR 650',
        priceNum: 650,
        oldPrice: 'PKR 750',
        saveText: 'Save PKR 100',
        rating: 4.8,
        reviewsCount: 54,
        badge: 'Doctor Choice',
        sku: 'BC-CRM-100',
        stockStatus: 'In Stock',
        shortDesc: 'Zinc oxide protective barrier cream that instantly calms redness and seals out wetness for prompt rash recovery.',
        images: [
            'assets/diaper_cream.png',
            'assets/baby_wipes.png',
            'assets/baby_lotion.png',
            'assets/baby_products.png'
        ],
        variations: {
            size: { label: 'Size', default: '100g Tube', options: ['100g Tube', '150g Jar'] }
        },
        features: [
            { icon: 'shield-check', title: 'Zinc Oxide 15%', desc: 'Instant Shield' }
        ],
        descriptionBullets: [
            'Forms an immediate breathable protective layer',
            'Relieves discomfort from first application'
        ],
        specifications: [
            { label: 'Weight', value: '100 grams' }
        ],
        relatedIds: ['baby-wipes', 'baby-lotion', 'baby-shampoo', 'baby-powder']
    }
};

// Helper function to resolve product by ID
function getProductById(id) {
    if (!id) return PRODUCTS_DATABASE['mercedes-amg'];
    const lowerId = id.toLowerCase().trim();
    if (PRODUCTS_DATABASE[lowerId]) {
        return PRODUCTS_DATABASE[lowerId];
    }
    const keys = Object.keys(PRODUCTS_DATABASE);
    const foundKey = keys.find(k => k.includes(lowerId) || lowerId.includes(k));
    if (foundKey) {
        return PRODUCTS_DATABASE[foundKey];
    }
    return PRODUCTS_DATABASE['mercedes-amg'];
}
