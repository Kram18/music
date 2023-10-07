<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SongsModel;

class SongUploadController extends BaseController
{
    private $tblsongs;

    public function __construct() {
        helper('form');
        $this->tblsongs = new SongsModel();
    }

    public function index()
    {
        return view('SongUpload');
    }
    
    public function upload(){
        $file = $this->request->getFile('songFile');
        var_dump($file);

        $newFileName = $file->getRandomName();

        $data = [
            'SongName' => $file->getName(),
            'SongFileAddress' => $newFileName
        ];
        $rules = [
            'songFile' => [
                'uploaded[songFile]',
                'mime_in[songFile,audio/mpeg]', // Allow only MP3 files
                'max_size[songFile,10240]', // Maximum file size in kilobytes (adjust as needed)
                'ext_in[songFile,mp3]' // Allow only files with the .mp3 extension
            ]
        ];

        if($this->validate($rules)){
            if($file->isValid() && !$file->hasMoved()){
                if($file->move(FCPATH.'uploads\songs', $newFileName)){
                    echo 'File uploaded successfully';
                    $this->tblsongs->save($data);
                }else{
                    echo $file->getErrorString().' '.$file->getError();
                }
            }
        }else{
            $data['validation'] = $this->validator;
        }

        return redirect()->to('/uploadSongs');
    }
}
