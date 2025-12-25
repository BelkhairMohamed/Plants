-- ============================================
-- Add Three Philodendron Varieties
-- ============================================
-- This script adds three different types of Philodendron:
-- 1. Philodendron Birken
-- 2. Philodendron Sun Red
-- 3. Philodendron Hope Selloum

USE plants_management;

-- Philodendron Birken
INSERT INTO plant_catalog (
    common_name,
    scientific_name,
    description,
    difficulty_level,
    light_requirement,
    water_requirement,
    humidity_preference,
    temperature_min,
    temperature_max,
    recommended_for_beginners,
    default_watering_interval_days,
    default_fertilizing_interval_days,
    mature_plant_guide
) VALUES (
    'Philodendron Birken',
    'Philodendron birkin',
    'Le Philodendron Birken est une variété rare et élégante avec des feuilles vert foncé marquées de rayures blanches distinctives. Cette plante est un cultivar qui produit des feuilles uniques avec des motifs en rayures qui deviennent plus prononcés avec l\'âge. C\'est une plante d\'intérieur très recherchée pour son apparence distinctive.',
    'beginner',
    'medium',
    'medium',
    'medium',
    18,
    26,
    TRUE,
    7,
    30,
    'Arrosage: Arrosez lorsque le premier centimètre du sol est sec. Évitez l\'eau stagnante.\n\nLumière: Lumière indirecte brillante. Évitez la lumière directe du soleil.\n\nHumidité: Apprécie une humidité modérée. Brumisez les feuilles occasionnellement.\n\nEngrais: Fertilisez mensuellement pendant la saison de croissance (printemps-été).\n\nTaille: Taillez les feuilles mortes ou jaunies pour encourager une nouvelle croissance.'
);

-- Philodendron Sun Red
INSERT INTO plant_catalog (
    common_name,
    scientific_name,
    description,
    difficulty_level,
    light_requirement,
    water_requirement,
    humidity_preference,
    temperature_min,
    temperature_max,
    recommended_for_beginners,
    default_watering_interval_days,
    default_fertilizing_interval_days,
    mature_plant_guide
) VALUES (
    'Philodendron Sun Red',
    'Philodendron erubescens',
    'Le Philodendron Sun Red est une variété magnifique avec des feuilles qui présentent des teintes rouges et vertes vibrantes. Les nouvelles feuilles émergent souvent dans des tons de rouge ou de rose, créant un contraste saisissant avec les feuilles matures vertes. Cette plante est appréciée pour sa couleur unique et sa facilité d\'entretien.',
    'beginner',
    'medium',
    'medium',
    'medium',
    18,
    26,
    TRUE,
    7,
    30,
    'Arrosage: Maintenez le sol légèrement humide mais pas détrempé. Arrosez lorsque le sol est sec au toucher.\n\nLumière: Lumière indirecte brillante à moyenne. Peut tolérer une lumière plus faible.\n\nHumidité: Préfère une humidité modérée à élevée. Brumisez régulièrement pour maintenir l\'humidité.\n\nEngrais: Appliquez un engrais équilibré toutes les 4-6 semaines pendant la saison de croissance.\n\nSupport: Cette plante peut bénéficier d\'un tuteur ou d\'un support pour grimper.'
);

-- Philodendron Hope Selloum
INSERT INTO plant_catalog (
    common_name,
    scientific_name,
    description,
    difficulty_level,
    light_requirement,
    water_requirement,
    humidity_preference,
    temperature_min,
    temperature_max,
    recommended_for_beginners,
    default_watering_interval_days,
    default_fertilizing_interval_days,
    mature_plant_guide
) VALUES (
    'Philodendron Hope Selloum',
    'Philodendron bipinnatifidum',
    'Le Philodendron Hope Selloum est une plante imposante avec de grandes feuilles profondément lobées qui créent un effet tropical spectaculaire. Cette variété peut atteindre une taille considérable et est parfaite comme plante d\'accent dans de grands espaces. Les feuilles vert foncé brillantes et profondément découpées donnent à cette plante un aspect exotique et luxuriant.',
    'intermediate',
    'medium',
    'medium',
    'high',
    18,
    27,
    FALSE,
    7,
    30,
    'Arrosage: Arrosez abondamment lorsque le sol est sec au toucher, mais laissez le sol sécher entre les arrosages.\n\nLumière: Lumière indirecte brillante. Peut tolérer une lumière moyenne mais poussera plus lentement.\n\nHumidité: Nécessite une humidité élevée. Utilisez un humidificateur ou placez sur un plateau de galets avec de l\'eau.\n\nEspace: Cette plante peut devenir très grande. Assurez-vous d\'avoir suffisamment d\'espace pour sa croissance.\n\nEngrais: Fertilisez toutes les 4-6 semaines pendant la saison de croissance avec un engrais équilibré.\n\nNettoyage: Essuyez régulièrement les grandes feuilles pour maintenir leur éclat.'
);

-- Display confirmation
SELECT 
    id,
    common_name,
    scientific_name,
    difficulty_level,
    light_requirement
FROM plant_catalog
WHERE common_name LIKE 'Philodendron%'
ORDER BY id DESC
LIMIT 3;

