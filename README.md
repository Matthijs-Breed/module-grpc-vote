Add the following to your project's composer.json to autoload the generated classes.
Since GRPC enforces this namespace, there is no option to regularely autoload these.

```
"autoload":
  ...
  "psr-4": {
  ...
    "Voting\\": "vendor/experius/module-grpc-vote/Generated/Voting",
    "GPBMetadata\\": "vendor/experius/module-grpc-vote/Generated/GPBMetadata"
  }
```
