<?php
namespace App\Controllers;
use App\Models\ArtistModel;
class ArtistController extends BaseController
{
    public function index()
    {
        $artistModel = new ArtistModel();
        
        $data = [
            'artists' => $artistModel->getPaginatedArtists(15),
            'pager'   => $artistModel->pager
        ];
        return view('artists/index', $data);
    }
    public function details($artistId)
    {
        $artistId = (int)$artistId;
        $artistModel = new ArtistModel();
        $tracks = $artistModel->getArtistDetails($artistId);
        
        if (empty($tracks)) {
            return $this->response->setJSON(['error' => 'Artist not found or has no tracks']);
        }
        
        $albums = [];
        foreach ($tracks as $track) {
            $albumName = $track['album_name'];
            if (!isset($albums[$albumName])) {
                $albums[$albumName] = [];
            }
            $albums[$albumName][] = $track;
        }
        
        $categorized = [
            'Singles' => [],
            'EPs'     => [],
            'Albums'  => []
        ];
        
        foreach ($albums as $albumName => $albumTracks) {
            $count = count($albumTracks);
            $albumData = [
                'name' => $albumName,
                'release_date' => $albumTracks[0]['releaseDate'] ?? 'N/A',
                'tracks' => $albumTracks
            ];
            
            if ($count >= 1 && $count <= 3) {
                $categorized['Singles'][] = $albumData;
            } elseif ($count >= 4 && $count <= 6) {
                $categorized['EPs'][] = $albumData;
            } else {
                $categorized['Albums'][] = $albumData;
            }
        }
        
        return $this->response->setJSON([
            'artist_id' => $artistId,
            'releases' => $categorized
        ]);
    }
}