<?php include __DIR__ . '/../../partials/header.php'; ?>
<link rel="stylesheet" href="/assets/css/dance.css">

<?php
$featuredGenres = [
    'Hardwell' => 'Dance & Big room house',
    'Armin van Buuren' => 'Progressive trance & melodic techno',
    'Martin Garrix' => 'Dance / Electronic',
    'Tiësto' => 'Trance, Techno, House & Electro',
    'Nicky Romero' => 'Electro House & Progressive House',
    'Afrojack' => 'House & Electro House',
];

// Read selected filters from URL
$selectedDj = $_GET['dj'] ?? '';
$selectedVenue = $_GET['venue'] ?? '';

// Build filter dropdown options
$djOptions = [];
$venueOptions = [];

foreach ($lineup as $item) {
    $dj = $item['dj'];
    $events = $item['events'];

    $djOptions[$dj->getId()] = $dj->getName();

    foreach ($events as $event) {
        if ($event->getVenueName()) {
            $venueOptions[$event->getVenueName()] = $event->getVenueName();
        }
    }
}

// Prepare grouped events by day
$eventsByDay = [
    'Friday 24th July 2026' => [],
    'Saturday 25th July 2026' => [],
    'Sunday 26th July 2026' => [],
];

// Apply DJ and venue filters before grouping
foreach ($lineup as $item) {
    $dj = $item['dj'];
    $events = $item['events'];

    // If a DJ is selected, skip all others
    if ($selectedDj !== '' && (string)$dj->getId() !== $selectedDj) {
        continue;
    }

    foreach ($events as $event) {
        // If a venue is selected, skip other venues
        if ($selectedVenue !== '' && $event->getVenueName() !== $selectedVenue) {
            continue;
        }

        $start = strtotime($event->getStartDateTime());
        $dayLabel = date('l jS F Y', $start);

        if (isset($eventsByDay[$dayLabel])) {
            $eventsByDay[$dayLabel][] = [
                'dj' => $dj,
                'event' => $event
            ];
        }
    }
}
?>

<main class="dance-page">

    <!-- HERO -->
    <section class="dance-hero">
    <div class="dance-hero-overlay">

        <div class="dance-hero-content">
            <h1>Haarlem Dance</h1>

            <p>
                Haarlem dance this summer from July 24-26th 2026 invites you to unforgettable
                3 days of dance nights with internationally recognized legendary Dutch DJs.
            </p>

            
        </div>

    </div>
</section>

    <!-- ABOUT -->
    <section class="dance-about-section">
        

        <div class="dance-container dance-about-grid">
            <div class="dance-about-text">
                <h2>About Haarlem Dance</h2>

                <p>
                    Haarlem Dance brings powerful beats and top DJs to Haarlem this summer.
                    From 23rd to 26th July, enjoy four days of high-energy performances, great vibes,
                    and unforgettable moments. Come dance with friends and feel the music take over the city.
                </p>

                <p>
                    Get ready for massive beats and nonstop energy as world-class DJs take over the Dance Fest.
                    From euphoric build-ups to explosive drops, every set is designed to make you move.
                    Expect big names, unforgettable moments, and a crowd that lives for the music.
                    This is where the night comes alive and the party never slows down. 🎶
                </p>
            </div>

            <aside class="dance-quote-card">
                <p>
                    <strong>DANCE!</strong> brings the sound of the world to Haarlem.<br>
                    Create your moments.<br>
                    Feel every drop.
                </p>
            </aside>
        </div>
    </section>
    
    <!-- FEATURED DJS -->
    <section class="dance-featured-section">
        <div class="dance-container">
            <h2 class="dance-section-title">Featured DJs</h2>

            <?php
            $featuredDjs = [];
            foreach ($lineup as $item) {
                $dj = $item['dj'];
                $featuredDjs[$dj->getId()] = $dj;
            }
            ?>

            <?php if (empty($featuredDjs)): ?>
                <p class="dance-empty-text">No dance DJs available at the moment.</p>
            <?php else: ?>
                <div class="dance-featured-card">
                    <div class="dance-dj-grid">
                        <?php foreach ($featuredDjs as $dj): ?>
    <div class="dance-dj-item">
        <div class="dance-dj-image-wrap">
<?php
$djImages = [
    'Hardwell' => '/assets/images/dance/Hardwel.jpg',
    'Armin van Buuren' => '/assets/images/Armin van buuren.jpg',
    'Martin Garrix' => '/assets/images/dance/Martin-Garrix-2024-01.jpg',
    'Tiësto' => '/assets/images/dance/Tiesto.png',
    'Nicky Romero' => '/assets/images/Nicky.png.png',
    'Afrojack' => '/assets/images/dance/Afrojack.png',
];

$imagePath = $djImages[$dj->getName()] ?? '/assets/images/default-dj.jpg';
?>
<img
    src="<?= htmlspecialchars($imagePath) ?>"
    alt="<?= htmlspecialchars($dj->getName()) ?>"
>
           
        </div>

        <h3><?= htmlspecialchars($dj->getName()) ?></h3>

        <p class="dance-dj-genre">
            <?= htmlspecialchars($featuredGenres[$dj->getName()] ?? 'Dance Artist') ?>
        </p>

        <a href="/dance/<?= $dj->getId(); ?>" class="dance-small-btn">More Info</a>
    </div>
<?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- PROGRAM SCHEDULE -->
    <section class="dance-schedule-section" id="program-schedule">
    <div class="dance-container">
        <h2 class="dance-section-title dance-schedule-title-left">Program Schedule</h2>
        <p class="dance-schedule-subtitle">
            Filter Events by days, dates, All Access &amp; featured DJs
        </p>

        <!-- top filter chips -->
        <div class="dance-filter-row" id="day-filter-buttons">
            <button type="button" class="dance-filter-chip active" data-day="all">All Days</button>
            <button type="button" class="dance-filter-chip" data-day="Friday 24th July 2026">Friday 24th</button>
            <button type="button" class="dance-filter-chip" data-day="Saturday 25th July 2026">Saturday 25th</button>
            <button type="button" class="dance-filter-chip" data-day="Sunday 26th July 2026">Sunday 26th</button>
            <button type="button" class="dance-filter-chip" data-day="passes">All Access Passes</button>
        </div>

        <!-- DJ and venue filters -->
        <form method="GET" action="/dance#program-schedule" class="dance-filter-form">
            <div class="dance-dual-filter-box">

                <div class="dance-filter-group">
                    <label for="dj" class="dance-filter-label">🎵 Filter by DJ</label>
                    <select name="dj" id="dj" class="dance-filter-select" onchange="submitDanceFilterForm(this.form)">
                        <option value="">Select DJ</option>
                        <?php foreach ($djOptions as $djId => $djName): ?>
                            <option value="<?= htmlspecialchars((string)$djId) ?>" <?= $selectedDj === (string)$djId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($djName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="dance-filter-group">
                    <label for="venue" class="dance-filter-label">📍 Filter by Venue</label>
                    <select name="venue" id="venue" class="dance-filter-select" onchange="this.form.submit()">
                        <option value="">Venues</option>
                        <?php foreach ($venueOptions as $venueName): ?>
                            <option value="<?= htmlspecialchars($venueName) ?>" <?= $selectedVenue === $venueName ? 'selected' : '' ?>>
                                <?= htmlspecialchars($venueName) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <?php if ($selectedDj !== '' || $selectedVenue !== ''): ?>
                <div class="dance-filter-actions">
                    <a href="/dance#program-schedule" class="dance-clear-filter-btn">Clear Filters</a>
                </div>
            <?php endif; ?>
        </form>

        <?php
        $hasAnyEvents = false;
        foreach ($eventsByDay as $items) {
            if (!empty($items)) {
                $hasAnyEvents = true;
                break;
            }
        }
        ?>

        <?php if (!$hasAnyEvents): ?>
            <p class="dance-empty-text">No schedule matches the selected filters.</p>
        <?php endif; ?>

        <?php foreach ($eventsByDay as $day => $items): ?>
            <?php if (empty($items)) continue; ?>

            <div class="dance-day-block" data-day-block="<?= htmlspecialchars($day) ?>">
                <h3 class="dance-day-title"><?= htmlspecialchars($day) ?></h3>

                <div class="dance-day-grid">
                    <div class="dance-events-column">
                        <?php foreach ($items as $entry): ?>
                            <?php
                                $dj = $entry['dj'];
                                $event = $entry['event'];

                                $startTime = date('H:i', strtotime($event->getStartDateTime()));
                                $endTime = $event->getEndDateTime()
                                    ? date('H:i', strtotime($event->getEndDateTime()))
                                    : null;
                            ?>
                            <div class="dance-event-card">
                                <div class="dance-event-image">
                                    <img
                                        src="<?= htmlspecialchars($dj->getImageUrl() ?? '/assets/images/default-dj.jpg') ?>"
                                        alt="<?= htmlspecialchars($dj->getName()) ?>"
                                    >
                                </div>

                                <div class="dance-event-main">
                                    <div class="dance-event-top">
                                        <h4>
                                            <?= htmlspecialchars(
                                                $event->getDisplayTitle()
                                                    ? $event->getDisplayTitle()
                                                    : $dj->getName()
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
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="dance-pass-column" data-pass-column="<?= htmlspecialchars($day) ?>">
                        <?php if ($day === 'Friday 24th July 2026'): ?>
                            <div class="dance-pass-card">
    <h4>All Access Pass for 3 Days</h4>
    <p>All Shows &amp; exclusive B2B Sets</p>
    <div class="dance-pass-price">€150</div>

    <form method="POST" action="/addTicket" class="dance-program-form">
        <input type="hidden" name="event_id" value="1001">
        <input type="hidden" name="event_type" value="dance">
        <input type="hidden" name="is_pass" value="1">

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

                            <div class="dance-pass-card">
    <h4>All Access Pass for 3 Days</h4>
    <p>All Shows &amp; exclusive B2B Sets</p>
    <div class="dance-pass-price">€150</div>

    <form method="POST" action="/addTicket" class="dance-program-form">
        <input type="hidden" name="event_id" value="1001">
        <input type="hidden" name="event_type" value="dance">
        <input type="hidden" name="is_pass" value="1">

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
                        <?php elseif ($day === 'Saturday 25th July 2026'): ?>
                            <div class="dance-pass-card">
    <h4>All Day Access Pass Saturday</h4>
    <p>Party all night long with Access</p>
    <div class="dance-pass-price">€150</div>

    <form method="POST" action="/addTicket" class="dance-program-form">
        <input type="hidden" name="event_id" value="1003">
        <input type="hidden" name="event_type" value="dance">
        <input type="hidden" name="is_pass" value="1">

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
                        <?php elseif ($day === 'Sunday 26th July 2026'): ?>
                           <div class="dance-pass-card">
    <h4>All Day Access Pass Sunday only</h4>
    <p>Party all night long with Access</p>
    <div class="dance-pass-price">€150</div>

    <form method="POST" action="/addTicket" class="dance-program-form">
        <input type="hidden" name="event_id" value="1004">
        <input type="hidden" name="event_type" value="dance">
        <input type="hidden" name="is_pass" value="1">

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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('#day-filter-buttons .dance-filter-chip');
    const dayBlocks = document.querySelectorAll('[data-day-block]');
    const passColumns = document.querySelectorAll('[data-pass-column]');
    const scheduleSection = document.getElementById('program-schedule');

    function applyDayFilter(selectedDay) {
        dayBlocks.forEach(block => {
            const blockDay = block.getAttribute('data-day-block');
            const passColumn = block.querySelector('.dance-pass-column');

            if (selectedDay === 'all') {
                block.style.display = '';
                if (passColumn) passColumn.style.display = 'flex';
                return;
            }

            if (selectedDay === 'passes') {
                block.style.display = '';
                const currentDay = block.getAttribute('data-day-block');
                if (passColumn) {
                    passColumn.style.display = 'flex';
                }

                const eventsColumn = block.querySelector('.dance-events-column');
                if (eventsColumn) {
                    eventsColumn.style.display = 'none';
                }
                return;
            }

            const eventsColumn = block.querySelector('.dance-events-column');
            if (eventsColumn) {
                eventsColumn.style.display = 'flex';
            }

            if (blockDay === selectedDay) {
                block.style.display = '';
                if (passColumn) passColumn.style.display = 'flex';
            } else {
                block.style.display = 'none';
            }
        });

        if (selectedDay !== 'passes') {
            dayBlocks.forEach(block => {
                const eventsColumn = block.querySelector('.dance-events-column');
                if (eventsColumn) {
                    eventsColumn.style.display = 'flex';
                }
            });
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const selectedDay = this.getAttribute('data-day');
            applyDayFilter(selectedDay);

            if (scheduleSection) {
                scheduleSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    const hash = window.location.hash;
    if (hash === '#program-schedule' && scheduleSection) {
        setTimeout(() => {
            scheduleSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    }
});

function submitDanceFilterForm(form) {
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();
    window.location.href = '/dance?' + params + '#program-schedule';
}

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