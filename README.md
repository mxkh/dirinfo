[![Build Status](https://travis-ci.org/mxkh/dirinfo.svg?branch=master)](https://travis-ci.org/mxkh/dirinfo)

This is library helps you get a directory statistic

- What size of directory
- How many files in directory
- How many files in the directory have the same content

Before usage:

- If you not set path, path was setted at current working directory by default


Usage:

Get simple associative array

```php
$directory = new Directory();
$output = $directory->list();

Output:

Array
(
    [root_1] => Array
        (
            [size] => 102
            [files] => 1
            [sameFiles] => 0
        )

    [root_1_2] => Array
        (
            [size] => 170
            [files] => 3
            [sameFiles] => 2
        )

    [root_2] => Array
        (
            [size] => 102
            [files] => 1
            [sameFiles] => 0
        )

    [root_2_1] => Array
        (
            [size] => 170
            [files] => 3
            [sameFiles] => 0
        )

    [root_2_2] => Array
        (
            [size] => 204
            [files] => 4
            [sameFiles] => 3
        )

    [root_3_1] => Array
        (
            [size] => 136
            [files] => 2
            [sameFiles] => 2
        )

)
```

Get tree array of directories

```php
$directory = new Directory();
$output = $directory->asTree()->list();

Output:

Array
(
    [root_1] => Array
        (
            [size] => 102
            [files] => 1
            [sameFiles] => 0
            [root_1_2] => Array
                (
                    [size] => 170
                    [files] => 3
                    [sameFiles] => 2
                )

        )

    [root_2] => Array
        (
            [size] => 102
            [files] => 1
            [sameFiles] => 0
            [root_2_1] => Array
                (
                    [size] => 170
                    [files] => 3
                    [sameFiles] => 0
                    [root_2_2] => Array
                        (
                            [size] => 204
                            [files] => 4
                            [sameFiles] => 3
                        )

                    [root_3_1] => Array
                        (
                            [size] => 136
                            [files] => 2
                            [sameFiles] => 2
                        )

                )

        )

)
```

Get directories as JSON

```php
$directory = new Directory();
$output = $directory->asTree()->toJson()->list();

Output:

{  
   "root_1":{  
      "size":102,
      "files":1,
      "sameFiles":0,
      "root_1_2":{  
         "size":170,
         "files":3,
         "sameFiles":2
      }
   },
   "root_2":{  
      "size":102,
      "files":1,
      "sameFiles":0,
      "root_2_1":{  
         "size":170,
         "files":3,
         "sameFiles":0,
         "root_2_2":{  
            "size":204,
            "files":4,
            "sameFiles":3
         },
         "root_3_1":{  
            "size":136,
            "files":2,
            "sameFiles":2
         }
      }
   }
}
```