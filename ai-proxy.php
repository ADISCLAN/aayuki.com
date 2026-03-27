<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$history = $input['history'] ?? [];

if (empty($userMessage)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

// OPTION 1: Hugging Face (Free tier available)
// Get your free API key from: https://huggingface.co/settings/tokens
$API_KEY = 'YOUR_HUGGINGFACE_API_KEY_HERE';
$API_URL = 'https://api-inference.huggingface.co/models/mistralai/Mixtral-8x7B-Instruct-v0.1';

$systemPrompt = "You are Aayuki Export's expert AI product assistant. Aayuki Export (formerly Dura Export) is a premium plumbing fittings manufacturer and exporter based in Rajkot, Gujarat, India.

COMPANY:
- Name: Aayuki Export
- Address: Plot No. 14, Survey No. 149, Vavdi Industrial Area, Rajkot – 360004, Gujarat, India
- Phone: +91 98250 45496
- Email: info@duraexport.uk / anand@duraexport.uk
- Founded: 2000 | 25+ years experience | 50+ SKUs | 30+ countries

COMPLETE PRODUCT CATALOGUE:

=== SWR SERIES (PVC-U, 110 MM) ===
1. Tee — BS4514/BS4660/ISO3633/EN1329 — 611g
2. T/S Tee — 45° Branch SN4 / BS EN 1329-1 BD — 660g
3. 40° Yee — 45° Branch / BS EN 1329-1 BD — 927g
4. D/S Yee — 45° Branch SN4 / BS EN 1329-1 BD — 810g
5. SS/Access Pipe — BS4514/BS4660 — 638g
6. D/S Access Pipe — Pipe SN4 / BS EN 1329-1 BD — 740g
7. 90° Elbow — SN4 BS EN 1329-1 BD — 580g
8. W/Access Bend — Branch SN4 / BS EN 1329-1 BD — 753g
9. S/S Elbow W/Access — BS4514/BS4660/ISO3633/EN1329 — 682g
10. S/B Elbow — Pipe SN4 / BS EN 1329-1 BD — 508g
11. D/S Coupler — SN4 — 300g
12. D/S 45° Elbow — SN4 / BS EN 1329-1 BD — 368g
13. S/S 45° Elbow — BS4514/EN1329 — 360g
14. Access Cap Cover — 28g
15. 110MM Access Cap — 101g

=== PP FITTINGS (PPCP, Black/White/Grey, 10pcs/packet) ===
32 MM: Elbow SS 30g/40ctn | Elbow DS 36g/34ctn | Coupler 25g/54ctn | Tee 55g/16ctn | Reducer 32-40MM 18g/70ctn | End Cap 5g/182ctn | 45° Elbow 40g/26ctn
40 MM: Elbow DS 48g/24ctn | Elbow SS 39g/27ctn | Coupler 33g/39ctn | Tee 72g/12ctn | End Cap 6g/106ctn

=== FLOOR TRAPS ===
Square: 91mm hole | 130×130mm top | 150×150mm base | 360g | Finishes: Matt, Glossy, PVD Gold, PVD Rose, PVD Black
Linear: 1ft to 4ft | same 5 finishes | 800g–2750g (includes 150g drain cap)

=== SHOWER PLATES ===
MS Shower Plate: Chrome, 400g, 48 small cartons — Branded/Without Brand
MS Douche Plate: 4MM, Chrome, 300g, 96 small cartons — Branded/Without Brand
Big S/S Plate: 4MM, Chrome & Black
Small S/S Duche Plate: 4MM, Chrome & Black
Brand: Tiler's Choice (or without brand for private label)

=== WASTE ADAPTERS (PVC, Black/Brown) ===
110×32MM — 139g, 90/ctn
110×40MM — 143g, 90/ctn
110×32×32MM — 173g, 70/ctn
110×32×40MM — 174g, 70/ctn
110×40×40MM DS — 179g, 70/ctn

=== PUSHFIT (PP, Black/Brown body, Black/Blue O-Ring) ===
15MM — 2g, 50pcs/pkt, 180pkt/ctn
22MM — 5g, 25pcs/pkt, 160pkt/ctn

=== PVC PIPES (White, 3M) ===
15MM 700g | 20MM 950g | 25MM 1450g | 32MM 1950g | 40MM 2350g | 50MM 3000g

=== WC KIT === 72g, 1pc/pkt, 250pkt/ctn

RESPONSE STYLE:
- Be warm, professional, knowledgeable and concise
- Format responses clearly using line breaks for readability
- For product lists use structured format with key specs
- For pricing/orders direct to: info@duraexport.uk or +91 98250 45496
- Mention other pages when relevant: Products page for full catalogue, Contact page for quotes
- SN4 = Stiffness Class 4, suitable for underground drainage
- Always offer to help further";

// Build conversation context
$conversationText = $systemPrompt . "\n\n";
foreach ($history as $msg) {
    $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
    $conversationText .= "$role: " . $msg['content'] . "\n\n";
}
$conversationText .= "User: $userMessage\n\nAssistant:";

$data = [
    'inputs' => $conversationText,
    'parameters' => [
        'max_new_tokens' => 1000,
        'temperature' => 0.7,
        'top_p' => 0.95,
        'return_full_text' => false
    ]
];

$ch = curl_init($API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $API_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'AI service unavailable', 'details' => $response]);
    exit;
}

$result = json_decode($response, true);

if (isset($result[0]['generated_text'])) {
    echo json_encode(['reply' => trim($result[0]['generated_text'])]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected response format', 'details' => $result]);
}
?>
