# The Converter Library
A class library to convert c# entity files to dart entity files.

The idea is to read and parse given file in c# and export dart files with .toJson .fromJson .toMap .fromMap .fromJsonArray methods and parsing json inputs in given entity.

Parser and the writer is planned as abstract classes. But it's a little bir hard to implement an abstract class that applies multiple programming languages. Oh the other hand oop concept is about the same. So, writer is more applicable for abstraction.

Writer will depend on template rendering. So a template engine shall be added to this.

In the end, this class may convert entities from various languages to various languages. It may also have some database reading or writing capabilities. 

### Install
 
To install this library as global, you can add path of `bin` directory to your `PATH` variable.   


### Usage
 
Currently converting with a config file is supportted.

``````

{
  "source": "entities",
  "sourceLang": "cs",
  "target": "export",
  "targetLang": "dart"
}

``````   

Run followig
```conv ```


### API

You can read the code for the moment.

#### TESTS
 
Tests may include checking if the code is the same after a few conversion.



