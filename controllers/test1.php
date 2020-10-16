<?php


$comments = [
    ['id'=> 1, 'files' => [1, 2]],
    ['id'=> 2, 'files' => [1]],
    ['id'=> 3, 'files' => [3]],
    ['id'=> 4, 'files' => null],
    ['id'=> 5, 'files' => null],
    ['id'=> 6, 'files' => [3, 4]],
];

$mock = [
    [
        'files'    => [1, 2],
        'comments' => [1, 2]
    ],
    [
        'files'    => [3, 4],
        'comments' => [3, 6]
    ],
    [
        'files'    => null,
        'comments' => [4, 5]
    ]
];

$files = [
    ['id' => 1],
    ['id' => 2],
    ['id' => 3],
]

    [1,2,3]