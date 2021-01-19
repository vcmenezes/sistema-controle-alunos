<?php

namespace App\Models;

use Core\Model;
use DateTime;

/**
 * @property int $id
 * @property string $nome
 * @property string $situacao
 * @property string $endereco
 * @property DateTime $data
 */
class Escola extends Model
{
    protected ?string $table = 'escolas';
}
