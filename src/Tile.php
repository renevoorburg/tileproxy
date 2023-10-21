<?php declare(strict_types=1);
class Tile
{
    private string $tileset;
    private int $zoom;
    private int $x;
    private int $y;
    private string $uri = '';
    private string $file_extension;
    private bool $valid = true;

    public function __construct(string $uri, array $sources)
    {
        preg_match('/\\.[^.\\s]{3,4}$/', $uri, $ext);
        $this->file_extension = (string)$ext[0];
        $tileparts = explode("/", preg_replace('/'.$this->file_extension.'$/', '', $uri));

        $this->tileset = $tileparts[1];
        $this->zoom = (int) $tileparts[2];
        $this->x = (int) $tileparts[3];
        $this->y = (int) $tileparts[4];

        $this->valid = $this->valid && (count($tileparts) === 5);
        $this->valid = $this->valid && key_exists($this->tileset, $sources);
        $this->valid = $this->valid && ((string) $this->zoom ===  $tileparts[2])
            && ($this->zoom <= $sources[$this->tileset]["maxZoom"]);
        $this->valid = $this->valid && ((string) $this->x ===  $tileparts[3]);
        $this->valid = $this->valid && ((string) $this->y ===  $tileparts[4]);

        if($this->valid) {
            $this->uri = sprintf($sources[$this->tileset]["source"], $this->zoom, $this->x, $this->y);
        }
    }

    public function isValid() :bool
    {
        return $this->valid;
    }

    public function getUri() :string
    {
        return $this->uri;
    }

    public function getTileset() :string
    {
        return $this->tileset;
    }

    public function getPathName() : string
    {
        return $this->tileset . '/' . (string) $this->zoom . '/' . (string) $this->x;
    }

    public function getFileName() : string
    {
        return (string) $this->y . $this->file_extension;
    }
}