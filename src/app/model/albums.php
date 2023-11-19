<?php

namespace app\model\albums;

use app\model\db\albums_table;

/**
 * Parameters of an album
 */
const param_id = 'id',
      param_title = 'title',
      param_artist = 'artist';

function list_albums(): array {
    return albums_table\select();
}

function add_album(array $data): void {
    albums_table\insert($data);
}

function get_album(int $id): ?array {
    return albums_table\select(['id' => $id])[0] ?? null;
}

function delete_album(int $id): void {
    albums_table\delete(['id' => $id]);
}

function edit_album(int $id, array $data): void {
    albums_table\update($data, ['id' => $id]);
}
