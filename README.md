# Money Field for Laravel Nova

Custom money field for a Laravel Nova application I am working on. The default Currency field provided by Nova didn't quite cut the mustard for me so ended up creating this.

## Installing

You can install the package into your Nova application via composer:

```
compser require Jam0r85/nova-money
```

## Using
In your \App\Nova resource file, add the following into your Fields method:

```
public function fields (Request $request)
{
	return [
		Money::make('Price'),
		// ...
	];
}
```
You can optionally add the column name to be used in storage if different from the display name as per any Nova field:
```
Money::make('Price', 'price_column');
```
The default currency is **GBP** but can be changed per field using the currency() method:-
```
Money::make('Price')->currency('USD');
```
The default locale is **en_GB** but can be changed per field using the locale() method:-
```
Money::make('Price')->locale('en_US');
```
By default we assume you are storing values as minor units (eg. £100 = 10000p) but you can change this with the notMinor() method:-
```
Money::make('Price')->notMinor();
```

