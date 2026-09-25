<?php
/**
 * Central content store for the Dunamis Media one-page site.
 * Edit copy here – the templates in /sections read from these arrays.
 */

$SITE = [
    'name'      => 'Dunamis Media',
    'legal'     => 'Dunamis Media Company Limited',
    'tagline'   => 'Creative thinking, crafted beautifully.',
    'email'     => 'dunamismediacompanylimited@gmail.com',
    'phones'    => ['+256 701 509 103', '+256 702 573 822'],
    'address'   => 'Martyrs Mall, Plot 1667, Kyaliwajjala – Namugongo, Kira Road',
    'logo'      => 'assets/img/brand/dunamis-logo.png',
    'wordmark'  => 'assets/img/brand/dunamis-wordmark.png',
    // Hero plays through these in order, looping back to the first once the
    // last one ends. Drop more clips in assets/video/ and add them here.
    'hero_videos' => [
        'assets/video/hero-1.mp4',
        'assets/video/hero-2.mp4',
    ],
    'hero_poster' => 'assets/img/photos/marketing-strategy.jpg',
    'socials'   => [
        ['label' => 'Instagram', 'handle' => '@dunamismediaofficial', 'url' => 'https://instagram.com/dunamismediaofficial'],
        ['label' => 'Twitter / X', 'handle' => '@dunamismediaco', 'url' => 'https://x.com/dunamismediaco'],
    ],
];

$NAV = [
    ['label' => 'Work',        'href' => '#work'],
    ['label' => 'Services',    'href' => '#services'],
    ['label' => 'Studio',      'href' => '#studio'],
    ['label' => 'Events',      'href' => '#events'],
    ['label' => 'Contact',     'href' => '#contact'],
];

// Upcoming events / activations. Placeholder dates below — swap in the real
// ones (or clear the array back to []) before this goes live; the section
// falls back to a "nothing scheduled" state automatically when it's empty.
$EVENTS = [
    ['title' => 'Brand Strategy Workshop', 'day' => '10', 'month' => 'Oct', 'year' => '2026',
     'location' => 'Dunamis Media, Kira Road, Kampala', 'tag' => 'Workshop',
     'desc' => 'A hands-on half-day session on building a brand strategy that actually drives sales — for founders and marketing leads.',
     'link' => '#contact', 'link_label' => 'Reserve a seat'],
    ['title' => 'Kampala Creatives & Brands Mixer', 'day' => '14', 'month' => 'Nov', 'year' => '2026',
     'location' => 'Kampala, Uganda', 'tag' => 'Meetup',
     'desc' => 'An evening of informal networking for marketers, creatives and brand owners across the city.',
     'link' => '#contact', 'link_label' => 'RSVP'],
    ['title' => 'Year-End Campaign Planning Clinic', 'day' => '05', 'month' => 'Dec', 'year' => '2026',
     'location' => 'Dunamis Media, Kira Road, Kampala', 'tag' => 'Clinic',
     'desc' => 'Free 30-minute slots with our strategy team to map out your Q1 marketing calendar.',
     'link' => '#contact', 'link_label' => 'Book a slot'],
];

$PROCESS = [
    ['no' => '01', 'title' => 'Discover',
     'desc' => 'Brief, brand audit and audience research — we find the real problem before we touch a design tool.'],
    ['no' => '02', 'title' => 'Strategize',
     'desc' => 'One plan ties creative, media and budget to a single measurable goal, agreed with you up front.'],
    ['no' => '03', 'title' => 'Create',
     'desc' => 'Design, film, copy and print produced in-house — on brand, reviewed with you at every stage.'],
    ['no' => '04', 'title' => 'Launch & Learn',
     'desc' => 'We place it, activate it, then report on what moved — and feed that back into the next round.'],
];

$STATS = [
    ['value' => '8',    'suffix' => '',  'label' => 'Full-service capabilities under one roof'],
    ['value' => '30',   'suffix' => '+', 'label' => 'Brands guided across East Africa'],
    ['value' => '10',   'suffix' => '+', 'label' => 'Years of combined comms leadership'],
    ['value' => '360',  'suffix' => '°', 'label' => 'Strategy, creative and media in sync'],
];

// 'img' backs the card with a photo; where we don't have one, 'tint' picks a
// branded gradient (a–e) instead. Both sit under a dark scrim + centred label.
$SERVICES = [
    ['no' => '01', 'title' => 'Brand & Commercial Printing', 'tint' => 'a',
     'img' => 'assets/img/services/branding-printing.jpg',
     'desc' => 'Identity systems, packaging and large-format print produced with a finish that holds up in the real world.'],
    ['no' => '02', 'title' => 'Digital Marketing', 'tint' => 'b',
     'img' => 'assets/img/services/digital-marketing.jpg',
     'desc' => 'Social, SEO and paid media built around a measurable funnel – not vanity metrics.'],
    ['no' => '03', 'title' => 'Content Creation', 'tint' => 'c',
     'img' => 'assets/img/services/content-creation.jpg',
     'desc' => 'Video, editorial and design assets shaped for each platform and every stage of the journey.'],
    ['no' => '04', 'title' => 'Media Buying', 'tint' => 'd',
     'img' => 'assets/img/services/media-buying.jpg',
     'desc' => 'Planning and negotiating placements across broadcast, outdoor and digital for maximum reach per shilling.'],
    ['no' => '05', 'title' => 'Experiential Marketing', 'tint' => 'a',
     'img' => 'assets/img/services/experiential-marketing.jpg',
     'desc' => 'Activations and events that turn an audience into participants and participants into advocates.'],
    ['no' => '06', 'title' => 'PR & Crisis Management', 'tint' => 'd',
     'img' => 'assets/img/services/pr-crisis.jpg',
     'desc' => 'Reputation strategy, media relations and rapid response when the story needs steering.'],
    ['no' => '07', 'title' => 'Video Production', 'tint' => 'e',
     'img' => 'assets/img/services/video-production.jpg',
     'desc' => 'Concept to final grade – commercials, documentaries and social cutdowns from one team.'],
];

$VALUES = [
    ['label' => 'Integrity',     'score' => 95],
    ['label' => 'Creativity',    'score' => 97],
    ['label' => 'Excellence',    'score' => 90],
    ['label' => 'Collaboration', 'score' => 90],
    ['label' => 'Innovation',    'score' => 95],
];

$TEAM = [
    ['name' => 'Gift Ayebare', 'role' => 'Director – Communications',
     'photo' => 'assets/img/team/gift-ayebare.jpg',
     'bio' => '10+ years in corporate communications, PR and media relations.'],
    ['name' => 'Katushabe Fiona', 'role' => 'Director – Operations',
     'photo' => 'assets/img/team/katushabe-fiona.jpg',
     'bio' => '10+ years in HR and administration across education, healthcare and hospitality.'],
];

$CLIENTS = [
    ['name' => 'Equatorial',       'logo' => 'assets/img/clients/equatorial.png'],
    ['name' => 'Eurofoam',         'logo' => 'assets/img/clients/eurofoam.png'],
    ['name' => 'Kaps',             'logo' => 'assets/img/clients/kaps.png'],
    ['name' => 'Kizza',            'logo' => 'assets/img/clients/kizza.png'],
    ['name' => 'Lynn Driving',     'logo' => 'assets/img/clients/lynn-driving.png'],
    ['name' => 'Nile Agro',        'logo' => 'assets/img/clients/nile-agro.jpg'],
    ['name' => 'Prestige Driving', 'logo' => 'assets/img/clients/prestige-driving.png'],
    ['name' => 'Reclaim Health',   'logo' => 'assets/img/clients/reclaim-health.png'],
    ['name' => 'SBA',              'logo' => 'assets/img/clients/sba.jpg'],
    ['name' => 'Vodka',            'logo' => 'assets/img/clients/vodka.png'],
];

$WORK = [
    ['title' => 'Nile Agro',        'kind' => 'Brand refresh · Packaging', 'tint' => 'a', 'img' => 'assets/img/photos/graphic-design.jpg'],
    ['title' => 'Reclaim Health',   'kind' => 'Campaign · Content',        'tint' => 'b', 'img' => 'assets/img/photos/marketing-strategy.jpg'],
    ['title' => 'Lynn Driving',     'kind' => 'Identity · Digital',        'tint' => 'c', 'img' => null],
    ['title' => 'Eurofoam',         'kind' => 'Media buying · Activation', 'tint' => 'd', 'img' => 'assets/img/photos/emails.jpg'],
    ['title' => 'Equatorial',       'kind' => 'PR · Video production',     'tint' => 'e', 'img' => 'assets/img/services/video-production-alt.jpg'],
    ['title' => 'Prestige Driving', 'kind' => 'Social · Performance',      'tint' => 'f', 'img' => null],
];

$TESTIMONIALS = [
    ['quote' => 'A game-changer for our brand. Every design came back strategically aligned to what we were trying to say.',
     'name' => 'Grace', 'meta' => 'Marketing Lead'],
    ['quote' => 'The brand refresh landed exactly where we hoped. Communication was clear the whole way through.',
     'name' => 'Paul', 'meta' => 'Founder', 'rating' => 4],
    ['quote' => 'A cohesive, modern approach and a genuinely collaborative process from first call to delivery.',
     'name' => 'George', 'meta' => 'Operations Director'],
    ['quote' => 'They combined strategy and design in a way that moved our numbers – engagement is measurably up.',
     'name' => 'Emma', 'meta' => 'Brand Manager'],
];

/* Helper */
function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
