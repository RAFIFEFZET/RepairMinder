<?php

namespace App\Models;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'periode_pemeliharaans';

    protected $fillable = [
        'periode',
        'kode_barang',
        'deskripsi',
        'tanggal_maintenance_selanjutnya',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'kode_barang', 'kode_barang');
    }

    public function inventarisDKV()
    {
        return $this->belongsTo(InventarisDKV::class, 'kode_barang', 'kode_barang');
    }

    public function inventarisSarpras()
    {
        return $this->belongsTo(InventarisSarpras::class, 'kode_barang', 'kode_barang');
    }
}
