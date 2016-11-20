## PDF generator based on Laravel PHP Framework

This application is for generating PDF files from JSON data.

Application uses https://www.pdflib.com/ library.
It changes the given css type locations to pdf that is
(x,y) of the html element location which starts from the left top corner to
(x,y) of pdflib to left bottom corner.
All test data located in projects public/ dir named as test.
The configuration should be by virtual hosts located in following DocumentRoot:
DocumentRoot [path to project]/generator/public

The structure of the JSON is following:
<code>
{
    "document": {
        "height": xxx,
        "width": xxx
    },
    "images": [
        {
            "position": {
                "height": xxx,
                "left": xxx,
                "top": xxx,
                "width": xxx
            },
            "uri": "DIR/FILENAME.EXTENSION"
        },...

    ],
    "texts": [
        {
            "color": "# +hex FORMAT",
            "position": {
                "height": XXX,
                "left": XXX,
                "top": XXX,
                "width": XXX
            },
            "text": "SIMPLE TEXT"
        },...
    ]
}
</code>
## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

### License

MIT

### Contacts

For contacting please use following email: emil.yeritsyan@gmail.com