<?php

namespace app\models\album;

use app\models\db\album_table;

/**
 * Parameters of an album
 */
const param_id = 'id',
      param_title = 'title',
      param_artist = 'artist';

function list_albums(): array {
    return album_table\select();
}

function add_album(array $data): void {
    album_table\insert($data);
}

function get_album(int $id): ?array {
    return album_table\select(['id' => $id])[0] ?? null;
}

function delete_album(int $id): void {
    album_table\delete(['id' => $id]);
}

function update_album(int $id, array $data): void {
    album_table\update($data, ['id' => $id]);
}
