# SimpleFileCache
simple file cache

<pre>
<code>
use FileCache;

$id = 'dragonball';
$fileCache = new FileCache($id);

$cachename = 'carrot';
// All types : object, array, string, int...
$values = ['powerCount'=>'12352312423', 'powerType'=>'Superscient_3'];
$timeSecond = '3600';
$fileCache->set($cachename, $values , $timeSecond);
</code>
</pre>


<pre>
<code>
use FileCache;

$id = 'dragonball';
$fileCache = new FileCache($id);

$cachename = 'carrot';
$cacheDatas = $fileCache->get($cachename);
/*
    result data:

    [
        'powerCount'=>'12352312423',
        'powerType'=>'Superscient_3'
    ]
*/
</code>
</pre>
