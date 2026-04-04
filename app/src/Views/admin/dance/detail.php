<?php include __DIR__ . '/../../partials/header.php'; ?>

<link rel="stylesheet" href="/assets/css/dance.css">

<?php
$djName = $dj->getName();

$genreText = match ($djName) {
    'Hardwell' => 'Dance & Big room house',
    'Armin van Buuren' => 'Progressive trance & melodic techno',
    'Martin Garrix' => 'Dance / Electronic',
    'Tiësto' => 'Trance, Techno, House & Electro',
    'Nicky Romero' => 'Electro House & Progressive House',
    'Afrojack' => 'House & Electro House',
    default => 'Dance Artist'
};

$rankText = match ($djName) {
    'Hardwell' => '#1 (2x)',
    'Armin van Buuren' => '#1 (3x)',
    'Martin Garrix' => '#1 (3x)',
    'Tiësto' => '#1 (3x)',
    'Nicky Romero' => '#20',
    'Afrojack' => '#10',
    default => 'Top DJ'
};

$listenersText = match ($djName) {
    'Hardwell' => '12M+',
    'Armin van Buuren' => '25M+',
    'Martin Garrix' => '30M+',
    'Tiësto' => '28M+',
    'Nicky Romero' => '10M+',
    'Afrojack' => '20M+',
    default => '10M+'
};

$highlights = match ($djName) {
    'Hardwell' => [
        'Voted #1 DJ in the world by DJ Mag in 2013 and 2014.',
        'Founded Revealed Recordings in 2010, now a leading EDM label.',
        'Headlined Ultra Music Festival multiple times.',
        'Launched successful radio show "Hardwell On Air".'
    ],
    'Armin van Buuren' => [
        'Built a global fanbase through high-energy touring and story-driven sets.',
        'Headlined major festivals worldwide.',
        'Became one of the most recognized names in trance worldwide.'
    ],
    'Martin Garrix' => [
        'Broke through globally at a young age with a defining festival track.',
        'Founded STMPD RCRDS for innovative electronic artists.',
        'Became one of the most influential figures in modern EDM.'
    ],
    'Tiësto' => [
        'First DJ to perform live at the Olympic Games.',
        'Headlined major festivals worldwide.',
        'Influenced generations of DJs across multiple EDM eras.'
    ],
    'Nicky Romero' => [
        'Rose to global recognition with high-energy festival tracks.',
        'Founder of Protocol Recordings.',
        'Regular performer at major dance festivals and club venues.'
    ],
    'Afrojack' => [
        'Known for bridging underground grooves with mainstream festival appeal.',
        'Collaborated with major global artists.',
        'Regular headliner at international festivals and club events.'
    ],
    default => [
        'Internationally recognized dance performer.',
        'Known for energetic live performances.'
    ]
};

$storyText = $dj->getDescription() ?? 'No story available.';

$galleryImages = [
    $dj->getImageUrl() ?? '/assets/images/default-dj.jpg',
    $dj->getImageUrl() ?? '/assets/images/default-dj.jpg',
    $dj->getImageUrl() ?? '/assets/images/default-dj.jpg'
];

$tracks = match ($djName) {
    'Hardwell' => [
        [
            'title' => 'Spaceman',
            'text' => 'Spaceman is one of Hardwell’s most iconic tracks and a defining anthem of the big-room house era.'
        ],
        [
            'title' => 'United We Are',
            'text' => 'United We Are represents Hardwell’s vision of unity through electronic music and showcases his signature festival sound.'
        ],
        [
            'title' => 'Apollo',
            'text' => 'Apollo is one of Hardwell’s most emotional and melodic tracks, blending uplifting chords with a powerful big-room structure.'
        ],
    ],
    'Armin van Buuren' => [
        [
            'title' => 'This Is What It Feels Like',
            'text' => 'One of Armin’s biggest crossover tracks, blending emotional vocals with a festival-ready trance structure.'
        ],
        [
            'title' => 'Blah Blah Blah',
            'text' => 'A crowd-control anthem designed for live shows, famous for its call-and-response energy.'
        ],
        [
            'title' => 'A State of Trance',
            'text' => 'More than a title, ASOT is Armin’s global sound identity and a major part of his legacy.'
        ],
    ],
    'Martin Garrix' => [
        [
            'title' => 'Animals',
            'text' => 'A genre-defining track that launched Martin Garrix onto the global stage.'
        ],
        [
            'title' => 'Forbidden Voices',
            'text' => 'A fan favourite instrumental known for emotional melody and progressive structure.'
        ],
        [
            'title' => 'Scared to Be Lonely',
            'text' => 'A crossover hit that blended pop vocals with electronic production.'
        ],
    ],
    'Tiësto' => [
        [
            'title' => 'Adagio for Strings',
            'text' => 'One of the most influential tracks in electronic music history and a defining moment in Tiësto’s career.'
        ],
        [
            'title' => 'Red Lights',
            'text' => 'A global hit that marked Tiësto’s move toward a more melodic, progressive festival sound.'
        ],
        [
            'title' => 'The Business',
            'text' => 'One of Tiësto’s biggest modern releases, hugely popular on streaming and social platforms.'
        ],
    ],
    'Nicky Romero' => [
        [
            'title' => 'Toulouse',
            'text' => 'Nicky Romero’s breakthrough track and one of the most recognizable festival anthems of the 2010s.'
        ],
        [
            'title' => 'I Could Be the One',
            'text' => 'A major collaboration blending progressive melodies with festival energy.'
        ],
        [
            'title' => 'Protocol Recordings',
            'text' => 'A creative platform reflecting Nicky Romero’s vision of energetic and forward-thinking dance music.'
        ],
    ],
    'Afrojack' => [
        [
            'title' => 'Take Over Control',
            'text' => 'One of Afrojack’s biggest global hits, combining catchy vocals with an energetic house groove.'
        ],
        [
            'title' => 'Replica',
            'text' => 'A track that highlights Afrojack’s club-focused roots with a driving bassline and minimal structure.'
        ],
        [
            'title' => 'Forget the World',
            'text' => 'An album that showcases Afrojack’s range from hard-hitting festival tracks to melodic productions.'
        ],
    ],
    default => [
        ['title' => 'Featured Track 1', 'text' => 'Featured release information.'],
        ['title' => 'Featured Track 2', 'text' => 'Featured release information.'],
        ['title' => 'Featured Track 3', 'text' => 'Featured release information.'],
    ]
};
?>

<main class="dance-detail-page">

    <!-- HERO -->
    <section class="dance-detail-hero-top">
        <div class="dance-container">
            <div class="dance-detail-banner">
                <img
                    src="<?= htmlspecialchars($dj->getImageUrl() ?? '/assets/images/default-dj.jpg') ?>"
                    alt="<?= htmlspecialchars($dj->getName()) ?>"
                >
            </div>

            <div class="dance-detail-intro">
                <h1><?= htmlspecialchars($djName) ?></h1>
                <p>
                    <?= htmlspecialchars($dj->getShortDescription() ?? 'Live performance at Haarlem Festival.') ?>
                </p>
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="dance-detail-main-section">
        <div class="dance-container">

            <div class="dance-detail-top-grid">
                <div class="dance-overview-card">
                    <h3>Artist Overview</h3>

                    <div class="dance-overview-stats">
                        <div class="dance-overview-stat">
                            <span class="dance-stat-label">DJ Mag Rank</span>
                            <strong><?= htmlspecialchars($rankText) ?></strong>
                        </div>

                        <div class="dance-overview-stat">
                            <span class="dance-stat-label">Monthly Listeners</span>
                            <strong><?= htmlspecialchars($listenersText) ?></strong>
                        </div>
                    </div>

                    <div class="dance-overview-genre">
                        <?= htmlspecialchars($genreText) ?>
                    </div>

                    <div class="dance-overview-highlights">
                        <h4>Career highlights</h4>
                        <ul>
                            <?php foreach ($highlights as $highlight): ?>
                                <li><?= htmlspecialchars($highlight) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="dance-story-card">
                    <h3>Story</h3>
                    <p><?= nl2br(htmlspecialchars($storyText)) ?></p>
                </div>
            </div>

            <div class="dance-gallery-section">
                <h3><?= htmlspecialchars($djName) ?> in pictures</h3>
                <div class="dance-gallery-grid">
                    <?php foreach ($galleryImages as $image): ?>
                        <div class="dance-gallery-item">
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($djName) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dance-tracks-section">
                <h3>Featured Tracks &amp; Albums</h3>

                <div class="dance-tracks-card">
                    <?php foreach ($tracks as $track): ?>
                        <div class="dance-track-row">
                            <div class="dance-track-cover">
                                <img src="<?= htmlspecialchars($dj->getImageUrl() ?? '/assets/images/default-dj.jpg') ?>" alt="<?= htmlspecialchars($track['title']) ?>">
                            </div>

                            <div class="dance-track-info">
                                <h4><?= htmlspecialchars($track['title']) ?></h4>
                                <p><?= htmlspecialchars($track['text']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="dance-perf-section">
                <h3><?= htmlspecialchars($djName) ?> Performance Schedule</h3>

                <?php if (empty($events)): ?>
                    <p class="dance-empty-text">No scheduled performances for this DJ yet.</p>
                <?php else: ?>
                    <div class="dance-detail-schedule-list">
                        <?php foreach ($events as $event): ?>
                            <?php
                                $startTime = date('H:i', strtotime($event->getStartDateTime()));
                                $endTime = $event->getEndDateTime() ? date('H:i', strtotime($event->getEndDateTime())) : null;
                                $eventDate = date('l jS F Y', strtotime($event->getStartDateTime()));
                            ?>
                            <div class="dance-detail-schedule-card">
                                <div class="dance-detail-schedule-image">
                                    <img
                                        src="<?= htmlspecialchars($dj->getImageUrl() ?? '/assets/images/default-dj.jpg') ?>"
                                        alt="<?= htmlspecialchars($djName) ?>"
                                    >
                                </div>

                                <div class="dance-detail-schedule-main">
                                    <div class="dance-detail-schedule-head">
                                        <h4>
                                            <?= htmlspecialchars(
                                                $event->getDisplayTitle()
                                                    ? $event->getDisplayTitle()
                                                    : $djName
                                            ) ?>
                                        </h4>
                                        <span class="dance-price">€<?= number_format($event->getPrice(), 2) ?></span>
                                    </div>

                                    <div class="dance-event-meta">
                                        <span>🕒 <?= $startTime ?><?= $endTime ? ' – ' . $endTime : '' ?></span>
                                        <span>📍 <?= htmlspecialchars($event->getVenueName() ?? 'TBA') ?></span>
                                    </div>

                                    <form method="POST" action="/addTicket" class="dance-program-form">
    <input type="hidden" name="event_id" value="<?= $event->getDanceEventID(); ?>">
    <input type="hidden" name="event_type" value="dance">

    <div class="dance-ticket-controls">
        <button type="button" class="dance-qty-btn" onclick="changeQuantity(this, -1)">−</button>

        <input
            type="number"
            name="number_of_people"
            value="1"
            min="1"
            class="dance-qty-input"
            readonly
        >

        <button type="button" class="dance-qty-btn" onclick="changeQuantity(this, 1)">+</button>
    </div>

    <button type="submit" class="dance-small-btn">+ Add to my program</button>
</form>
                                </div>

                                <div class="dance-detail-schedule-date">
                                    <?= htmlspecialchars($eventDate) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

</main>

<script>
function changeQuantity(button, amount) {
    const form = button.closest('form');
    const input = form.querySelector('.dance-qty-input');

    let currentValue = parseInt(input.value) || 1;
    currentValue += amount;

    if (currentValue < 1) {
        currentValue = 1;
    }

    input.value = currentValue;
}
</script>

<?php include __DIR__ . '/../../partials/footer.php'; ?>