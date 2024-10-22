<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

/**
 * @OA\Schema(
 *     schema="Jurusan",
 *     type="object",
 *
 *     @OA\Property(property="id_jurusan", type="integer", example=1),
 *     @OA\Property(property="jurusan_nama", type="string", example="Teknik Informatika"),
 *     @OA\Property(property="icon_type", type="string", example="Jurusan"),
 *     @OA\Property(property="jurusan_short", type="string", example="TI"),
 *     @OA\Property(property="jurusan_thumbnail", type="string", example="img/jurusan/gambar.jpg"),
 *     @OA\Property(property="jurusan_logo", type="string", example="img/jurusan/logo/gambar.jpg"),
 *     @OA\Property(
 *         property="prodi",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="nama_prodi", type="string", example="Teknik Informatika"),
 *         @OA\Property(property="prodi_short", type="string", example="TI"),
 *     ),
 *     @OA\Property(property="jurusan_text", type="string", example="Deskripsi jurusan Teknik Informatika"),
 * )
 */
class JurusanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbnailPath = 'img/jurusan/'.$this->jurusan_thumbnail;
        $logoPath = 'img/jurusan/logo/'.$this->jurusan_logo;
        $jurusan_thumbnail = File::exists(public_path($thumbnailPath)) ? $thumbnailPath : 'img/no_image.png';
<<<<<<< HEAD
        $jurusan_logo = $this->jurusan_logo ? (File::exists(public_path($logoPath)) ? $logoPath : 'img/no_image.png') : 'img/no_image.png';
=======
        $jurusan_logo = $this->jurusan_logo ?  (File::exists(public_path($logoPath)) ? $logoPath : 'img/no_image.png'): 'img/no_image.png';
>>>>>>> a8fc1a42 (fix file not found for jurusan logo)

        $cleanText = str_replace(["\r", "\n", "\t"], '', $this->extra_text);

        return [
            'id_jurusan' => $this->id_jurusan,
            'jurusan_nama' => $this->jurusan_nama,
            'icon_type' => 'Jurusan',
            'jurusan_short' => $this->jurusan_short,
            'jurusan_thumbnail' => $jurusan_thumbnail,
            'jurusan_logo' => $jurusan_logo,
            'prodi' => $this->prodis ? [
                'id' => $this->prodis->id_prodi,
                'nama_prodi' => $this->prodis->prodi_name,
                'prodi_short' => $this->prodis->prodi_short,
            ] : null,
            'jurusan_text' => $cleanText,
        ];
    }
}
