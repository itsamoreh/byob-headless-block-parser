# BYOB Headless Block Parser

This plugin converts links to backend pages and posts in Gutenberg blocks and Block Attributes for use in a headless frontend app.

## HTML

The following link in a block's content
```
<a href="https://wordpress-backend.example.com/hello-world">...</a>
```
Will be converted to
```
<a data-internal-link="true" href="https://nextjs-frontend.example.com/hello-world">...</a>"
```

The `data-internal-link` attribute is set so you can use the link with your framework's `<Link>` component.

## Block Attributes

The url in the following ACF Gutenberg block
```
<!-- wp:acf/byob-example-block {
    "name": "acf/byob-example-block",
    "data": {
        "primary-cta": {
            "title": "Sample Page",
            "url": "https:\/\/wordpress-backend.example.com\/hello-world",
            "target": ""
        },
    },
} /-->
```

Will be converted to
```
<!-- wp:acf/byob-example-block {
    "name": "acf/byob-example-block",
    "data": {
        "primary-cta": {
            "title": "Sample Page",
            "url": "https:\/\/nextjs-frontend.example.com\/hello-world",
            "target": "_blank"
        },
    },
} /-->
```

If using [ACF Link fields](https://www.advancedcustomfields.com/resources/link/), make sure editors check "Open link in a new tab" in order to determine whether or not to use your framework's `<Link>` component.

## 

Forked from [kellenmace/headless-block-parser](https://github.com/kellenmace/headless-block-parser).
