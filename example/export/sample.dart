
class Article {

            int ArticleId;
            String Title;
            double Price;
            Writer Writer;
            DateTime ReleaseTime;
    
    Article({
                    this.ArticleId ,
                    this.Title ,
                    this.Price ,
                    this.Writer ,
                    this.ReleaseTime ,
            });

    factory Article.fromJson(Map<String, dynamic> json) => new Article(
                    ArticleId: json["ArticleId"] == null ? null : int.parse(json["ArticleId"].toString()), 
                    Title:  json["Title"] == null ? null :  json["Title"].toString(), 
                    Price:  json["Price"] == null ? null :  double.parse(json["Price"].toString()), 
                    Writer:  json["Writer"] == null ? null :  Writer.fromJson(json["Writer"]), 
                    ReleaseTime:  json["ReleaseTime"] == null ? null :  DateTime.parse(json["ReleaseTime"].toString() ), 
            );

    Map<String, dynamic> toJson() => {
                    "ArticleId": ArticleId == null ? null : ArticleId,
                    "Title": Title == null ? null : Title,
                    "Price": Price == null ? null : Price,
                    "Writer": Writer == null ? null : Writer.toJson(),
                    "ReleaseTime": ReleaseTime == null ? null : ReleaseTime.toUtc().toString().substring(0,19),
            };


    }
class Writer {

            int ArticleId;
            List<Book> Books;
            List<String> Nicknames;
            DateTime BirthDate;
            List<DateTime> DateTimeArr;
    
    Writer({
                    this.ArticleId ,
                    this.Books ,
                    this.Nicknames ,
                    this.BirthDate ,
                    this.DateTimeArr ,
            });

    factory Writer.fromJson(Map<String, dynamic> json) => new Writer(
                    ArticleId: json["ArticleId"] == null ? null : int.parse(json["ArticleId"].toString()), 
                    Books:  json["Books"] == null ? null  : new List<Book>.from ( json["Books"].map( (x)=> Book.fromJson(x) ) ), 
                    Nicknames:  json["Nicknames"] == null ? null  : new List<String>.from ( json["Nicknames"].map( (x)=> x.toString() ) ), 
                    BirthDate:  json["BirthDate"] == null ? null :  DateTime.parse(json["BirthDate"].toString() ), 
                    DateTimeArr:  json["DateTimeArr"] == null ? null  : new List<DateTime>.from ( json["DateTimeArr"].map( (x)=> DateTime.parse( x.toString() ) ) ), 
            );

    Map<String, dynamic> toJson() => {
                    "ArticleId": ArticleId == null ? null : ArticleId,
                    "Books": Books == null ? null : new List<dynamic>.from(Books.map((x) => x.toJson())) ,
                    "Nicknames": Nicknames == null ? null : new List<dynamic>.from(Nicknames.map((x) => x)) ,
                    "BirthDate": BirthDate == null ? null : BirthDate.toUtc().toString().substring(0,19),
                    "DateTimeArr": DateTimeArr == null ? null : new List<dynamic>.from(DateTimeArr.map((x) => x.toUtc().toString().substring(0,19))) ,
            };


    }
