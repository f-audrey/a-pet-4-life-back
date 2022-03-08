<?php
namespace App\Models;

class Data
{
private $data = [

    [
        'category_name' => 'Associations',
        'picture' => 'associations.png',
        'description' => 'Pour ajouter, modifer ou supprimer des associations.',
    ],

    [
      'category_name' => 'Particuliers',
      'picture' => 'particuliers.png',
      'description' => 'Pour ajouter, modifer ou supprimer les particuliers.',
  ],

  [
    'category_name' => 'Espèces',
    'picture' => 'espèces.png',
    'description' => 'Pour ajouter, modifer ou supprimer les espèces.',
],
];

public function getAllData()
{
    return $this->data;
}
}