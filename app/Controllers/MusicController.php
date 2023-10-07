<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SongsModel;
use App\Models\PlaylistsModel;
use App\Models\PlaylistTracksModel;

class MediaController extends BaseController
{
    private $tblsongs;
    private $tblmusic;
    private $tblplaylist;

    function __construct(){
        $this->tblsongs = new SongsModel();
        $this->tblmusic = new PlaylistsModel();
        $this->tblplaylist = new PlaylistTracksModel();
    }

    public function index()
    {
        $data = [
            'tblsongs'=>$this->tblsongs->findAll(),
            'tblmusic' => $this->tblmusic->findAll()
        ];

        return view('MusicPlaylist\index', $data);
    }

    public function AddSongForm(){
        return redirect()->to('/uploadSongs');
    }

    public function searchSong(){

        $searchLike = $this->request->getVar('search');

        if(!empty($searchLike)){

            $data = [
                'tblsongs' => $this->tblsongs->like('SongName',$searchLike)->findAll(),
                'tblmusic' => $this->tblmusic->findAll()
            ];

            return view('MusicPlaylist\index', $data);
        }else{
            return redirect()->to('/');
        }
    }

    public function createPlaylist(){
        $data = [
            'PlaylistName' => $this->request->getVar('playlistName')
        ];

        $this->tblmusic->save($data);

        return redirect()->to('/');
    }

    public function addToPlaylist(){
        $data = [
            'Song_ID' => $this->request->getVar('musicID'),
            'Playlist_ID' => $this->request->getVar('tblmusic')
        ];

        $this->playlistTracks->save($data);

        return redirect()->to('/');
    }

    public function playtblmusiclist($id = null){

        $db = \Config\Database::connect();
        $builder = $db->table('tblsongs');

        $builder->select(['tblsongs.id', 'tblsongs.SongName', 'tblsongs.SongFileAddress', 'tblmusic.PlaylistName','tblmusic.playlist_id','tblmusic.playlist_track_id']);
        $builder->join('playlist_track', 'tblsongs.id = playlist_track.song_id');
        $builder->join('tblmusic', 'playlist_track.playlist_id = tblmusic.playlist_id');

        if ($id !== null) {
            $builder->where('tblmusic.playlist_id', $id);
        }

        $query = $builder->get();

        $data = [
            'tblsongs' => $this->tblsongs->findAll(),
            'tblmusic' => $this->tblmusic->findAll()
        ];

        if ($query) {
            $data['tblsongs'] = $query->getResultArray();
        } else {
            echo "Query failed";
        }
        
        return view('MusicPlaylist\index', $data);
    }

    public function deleteFromPlaylist($id = null){

        $this->tblplaylist->where('playlist_track_id',$id)->delete();

        return redirect()->to('/');
    }
}
}
