<?php

    try
    {
        $config = new \PHPVideoToolkit\Config(array(
            'temp_directory'              => './tmp',
            'ffmpeg'                      => '/usr/local/binffmpeg',
            'ffprobe'                     => '/usr/local/binffprobe',
            'yamdi'                       => '/usr/local/binyamdi',
            'qtfaststart'                 => '/usr/local/binqt-faststart',
            'gif_transcoder'              => 'php',
            'convert'                     => '/usr/local/binconvert',
            'gifsicle'                    => '/usr/local/bingifsicle',
            'php_exec_infinite_timelimit' => true,
        ));
    }
    catch(\PHPVideoToolkit\Exception $e)
    {
        echo '<h1>Config set errors</h1>';
        \PHPVideoToolkit\Trace::vars($e);
        exit;
    }

    $example_video_path = BASE.'examples/media/BigBuckBunny_320x180.mp4';
    $example_audio_path = BASE.'examples/media/Ballad_of_the_Sneak.mp3';
    