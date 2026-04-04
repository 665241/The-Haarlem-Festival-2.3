<?php

namespace App\Controllers;

use App\Services\Interfaces\IArtistService;
use App\Services\Interfaces\IDanceEventService;
use App\Models\Enums\EventTypeEnum;

use App\Services\ArtistService;
use App\Services\DanceEventService;
use App\Repositories\ArtistRepository;
use App\Repositories\DanceEventRepository;

class DanceController
{
    // Service to handle all artist (DJ) data
    private IArtistService $artistService;

    // Service to handle all dance event data
    private IDanceEventService $danceEventService;

    /**
     * Constructor
     * Since the router creates the controller without arguments,
     * we create the repository and service objects manually here.
     */
    public function __construct()
    {
        $this->artistService = new ArtistService(new ArtistRepository());
        $this->danceEventService = new DanceEventService(new DanceEventRepository());
    }

    /**
     * /dance
     * Shows the Dance homepage with all DJs and their events
     */
    public function index()
    {
        // Get all artists from database
        // On this page we treat them as DJs
        $artists = $this->artistService->getAllArtists();

        // This array will store only artists who have dance events
        $lineup = [];

        // Loop through each artist
        foreach ($artists as $dj) {

            // Get only this DJ's dance events
            $events = $this->danceEventService->getEventsForArtist(
                $dj->getId(),
                EventTypeEnum::DanceEvent
            );

            // Only include DJs who actually have dance events
            if (!empty($events)) {
                $lineup[] = [
                    'dj' => $dj,
                    'events' => $events
                ];
            }
        }

        // Load Dance homepage view
        include __DIR__ . '/../Views/admin/Dance/index.php';
    }

    public function adminIndex()
    {
        
    //  Check if user is logged in
    // If there is no user in the session, redirect to login page
    if (!isset($_SESSION['user'])) {
        header('Location: /login'); // send user to login page
        exit; // stop running the code
    }

     // Get all artists first
    $allArtists = $this->artistService->getAllArtists();

    // Keep only artists who have dance events
    $artists = [];

    foreach ($allArtists as $artist) {
        $artistEvents = $this->danceEventService->getEventsForArtist(
            $artist->getId(),
            EventTypeEnum::DanceEvent
        );

        if (!empty($artistEvents)) {
            $artists[] = $artist;
        }
    }

    // Get all dance events
    $events = $this->danceEventService->getAllDanceEvents();

    include __DIR__ . '/../Views/admin/Dance/adminIndex.php';
}

    /**
     * /dance/{id}
     * Shows one DJ detail page
     */
   public function detail($vars)
{
    // Get id from the URL
    $id = (int)$vars['id'];

    // Get the clicked DJ from the shared Artist table
    $dj = $this->artistService->getArtistById($id);

    // If no DJ exists with that ID, show 404
    if (!$dj) {
        http_response_code(404);
        echo 'DJ not found';
        return;
    }

    // Get all dance events for this DJ
    $events = $this->danceEventService->getEventsForArtist(
        $id,
        \App\Models\Enums\EventTypeEnum::DanceEvent
    );

    // Load the correct detail page
   include __DIR__ . '/../Views/admin/Dance/detail.php';
}
public function showCreateForm()
{
    // Open the Dance create artist form page
    // This loads the file where the admin can add a new dance artist
    include __DIR__ . '/../Views/admin/Dance/createArtist.php';
}


public function store()
{
    //  Check if the request is POST (form was submitted)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //  Handle image upload
        // 'image_file' = input name in form
        // 'artist' = folder/type (used inside your upload function)
        $fileName = $this->handleImageUpload('image_file', 'artist');

        //  Create a new Artist object
        $artist = new ArtistModel();

        //  Set artist name
        $artist->setName(trim($_POST['name'] ?? ''));

        //  Set short description
        $artist->setShortDescription(trim($_POST['short_description'] ?? ''));

        //  Set full description
        $artist->setDescription(trim($_POST['description'] ?? ''));

        //  If image was uploaded, save its path
        if ($fileName) {
            $artist->setImageUrl('/assets/uploads/dance/artists/' . $fileName);
        }

        //  Save artist using the service
        if ($this->artistService->createArtist($artist)) {

            //  Redirect back to admin page after success
            header('Location: /admin/dance?status=created');
            exit;
        }
    }

    //  If something fails, reload the form again
    include __DIR__ . '/../Views/admin/Dance/createArtist.php';
}

public function showEditForm($vars)
{
    //  Get the ID from the URL (e.g. /admin/dance/edit/5)
    $id = (int)$vars['id'];

    //  Get the artist from the database using the ID
    $artist = $this->artistService->getArtistById($id);

    //  If no artist is found, redirect back with an error
    if (!$artist) {
        header('Location: /admin/dance?error=notfound');
        exit;
    }

    //  Load the edit form page
    // The $artist will be available inside this view
    include __DIR__ . '/../Views/admin/Dance/editArtist.php';
}

//this is the save edited artist part.
//gets the artist by ID
//checks if that artist exists
//reads the edited form values
//uploads a new image if one was chosen
//updates the artist in the database
//redirects back to Dance admin page

public function update($vars)
{
    // Get the artist ID from the URL
    $id = (int)$vars['id'];

    // Find the artist in the database
    $artist = $this->artistService->getArtistById($id);

    // If artist does not exist, go back to Dance admin page
    if (!$artist) {
        header('Location: /admin/dance');
        exit;
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Update the artist name from the form
        $artist->setName(trim($_POST['name'] ?? ''));

        // Update the short description from the form
        $artist->setShortDescription(trim($_POST['short_description'] ?? ''));

        // Update the full description from the form
        $artist->setDescription(trim($_POST['description'] ?? ''));

        // Check if a new image was uploaded
        $newImage = $this->handleImageUpload('image_file', 'artist');

        // If there is a new image, update the image path
        if ($newImage) {
            $artist->setImageUrl('/assets/uploads/dance/artists/' . $newImage);
        }

        // Save the updated artist in the database
        if ($this->artistService->updateArtist($id, $artist)) {
            header('Location: /admin/dance?status=updated');
            exit;
        }
    }

    // If page is first opened, or update fails, show the edit form again
    include __DIR__ . '/../Views/admin/Dance/editArtist.php';
}

public function delete($vars)
{
    //  Get the artist ID from the URL
    $id = (int)$vars['id'];

    //  Delete the artist from the database
    $this->artistService->deleteArtist($id);

    //  Redirect back to the Dance admin page with a success message
    header('Location: /admin/dance?status=deleted');
    exit;
}
}