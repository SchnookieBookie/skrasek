<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtistModel extends Model
{
    protected $table            = 'artist';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Získá stránkovaný seznam interpretů z tabulky artist
     */
    public function getPaginatedArtists(int $perPage = 20)
    {
        return $this->select('id, name')
                    ->orderBy('name', 'ASC')
                    ->paginate($perPage);
    }

    /**
     * Získá všechny tracky pro daného interpreta pomocí JOINu s tabulkou album
     */
    public function getArtistDetails(int $artistId)
    {
        $builder = $this->db->table('track t');
        $builder->select('t.name AS track_name, t.duration, t.tempo, a.name AS album_name, a.releaseDate');
        $builder->join('album a', 't.album_id = a.id');
        $builder->where('t.artist_id', $artistId);
        $builder->orderBy('a.releaseDate', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}