<?php require_once __DIR__ . '../../partials/header.php'; ?>

<div class="festival-dark-page">
    <div class="container py-5 mt-5 program-container">
        <div class="row align-items-center mb-5 p-4 border-b-peach">
            <div class="col-md-8">
                <h1 class="hero-overlay-title text-light-peach">My Dashboard</h1>
                <p class="text-light-peach opacity-80">Welcome back, <?= htmlspecialchars($user->getFullName()) ?>. Manage your festival experience.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="/profile/edit" class="more-btn d-inline-block w-auto px-4">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="festival-dark-card text-center h-100">
                    <?php
                        $pic = $user->getProfilePicture();
                        $img = !empty($pic) ? $pic : "https://ui-avatars.com/api/?name=" . urlencode($user->getFullName()) . "&size=150&background=F4C7B8&color=3B0B00";
                    ?>
                    <div class="avatar-wrapper mb-3 d-inline-block">
                        <img src="<?= htmlspecialchars($img) ?>" class="profile-avatar-styled">
                    </div>
                    
                    <h4 class="fw-bold text-light-peach mb-0"><?= htmlspecialchars($user->getFullName()) ?></h4>
                    <p class="text-peach x-small mb-4">@<?= htmlspecialchars($user->getUserName()) ?></p>

                    <div class="text-start info-list pt-3 border-t-peach">
                        <div class="info-item mb-3">
                            <label class="section-label">Email Address</label>
                            <div class="fw-medium text-light-peach small"><?= htmlspecialchars($user->getEmail()) ?></div>
                        </div>
                        <div class="info-item mb-3">
                            <label class="section-label">Phone Number</label>
                            <div class="fw-medium text-light-peach small"><?= htmlspecialchars($user->getPhoneNumber() ?: 'Not linked') ?></div>
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <form method="POST" action="/profile/delete" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                            <button type="submit" class="btn btn-delete-custom btn-sm w-100">
                                <i class="bi bi-trash3 me-2"></i>Delete Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="festival-dark-card ticket-container-card p-0 overflow-hidden">
                    <div class="card-header-peach p-4">
                        <h4 class="fw-bold mb-0 text-dark-brown"><i class="bi bi-ticket-perforated me-2"></i>Purchased Tickets</h4>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table custom-dark-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Event</th>
                                    <th>Guests</th>
                                    <th>Schedule</th>
        
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($tickets as $ticket): 
                                        $event = $ticket->getEvent();
                                        $details = $event->getDetails();
                                        $type = $event->getEventType()->value;
                            
                                        
                                        $startTime = 'TBD';
                                        $venue = 'Festival Grounds';
                                        $title = 'Festival Event';
                                        $icon = 'bi-calendar-event';

                                        if ($type === 'jazzpass' && $details instanceof \App\Models\JazzPassModel) {
                                            $title = $details->getTitle();
                                            $venue = "All Jazz Venues";
                                            $startTime = "Festival Period";
                                            $icon = 'bi-stars';
                                        } elseif ($type === 'jazz' && is_array($details)) {
                                            $jazzEvent = $details['jazzEvent'] ?? null;
                                            $artist = $details['artist'] ?? null;
                                            $venueInfo = $details['venueInfo'] ?? null;
                                            $startTime = $jazzEvent ? $jazzEvent->getStartDateTime() : 'TBD';
                                            $title = $artist ? $artist->getName() : 'Jazz Artist';
                                            $venue = $venueInfo['VenueName'] ?? 'Jazz Venue';
                                            if (!empty($venueInfo['HallName'])) {
                                                $venue .= " - " . $venueInfo['HallName'];
                                            }
                                            $icon = 'bi-music-note-beamed';
                                        } elseif ($type === 'reservation' && $details instanceof \App\Models\Yummy\RestaurantModel) {
                                            $startTime = $details->getSessionData()->getStartTime();
                                            $venue = $details->getLocation();
                                            $title = $details->getName();
                                            $icon = 'bi-egg-fried';
                                        } elseif ($type === 'tour' && $details instanceof \App\Models\HistoryEventModel) {
                                            $startTime = $details->getStartTime();
                                            $venue = $details->getVenue()->getLocation();
                                            $title = "History Tour";
                                            $icon = 'bi-map';
                                        } elseif ($type === 'kids' && $details instanceof \App\Models\KidsEventModel) { // fix details
                                            $title = $details->getType(); 
                                            $venue = $details->getLocation();                                                                               
                                            $startTime = $details->getDay() . ' ' . $details->getStartTime();
                                            $icon = 'bi-balloon-heart';
                                        }
                                    ?>
                                    <tr class="ticket-row-styled">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center py-2">
                                            <div class="icon-box-peach me-3">
                                                <i class="bi <?= $icon ?>"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-light-peach"><?= htmlspecialchars($title) ?></div>
                                                <div class="text-peach x-small"><?= htmlspecialchars($venue) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-normal border"><?= $ticket->getNumberOfPeople() ?></span>
                                    </td>
                                    <td>
                                        <?php if ($startTime === "Festival Period"): ?>
                                            <span class="badge bg-soft-orange text-peach rounded-pill x-small fw-bold">All Access</span>
                                        <?php else: ?>
                                            <div class="fw-medium text-light-peach small"><?= date('D, M j', strtotime($startTime)) ?></div>
                                            <div class="text-peach x-small"><?= date('H:i', strtotime($startTime)) ?></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '../../partials/footer.php'; ?>