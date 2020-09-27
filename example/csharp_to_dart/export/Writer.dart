
import 'package:tms_mobile/entities/Book.dart';
export 'package:tms_mobile/entities/Book.dart';


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

    Map<String, dynamic> toJson(){
        Map<String, dynamic> _json = Map<String, dynamic>();

                    if( ArticleId != null ){
                        _json['"ArticleId"'] = "\"${ArticleId}\"";
            }
                    if( Books != null ){
                        _json['"Books"'] = new List<dynamic>.from(Books.map((x) => x.toJson())) ;
            }
                    if( Nicknames != null ){
                        _json['"Nicknames"'] = new List<dynamic>.from(Nicknames.map((x) => "\"${x}\"" )) ;
            }
                    if( BirthDate != null ){
                        _json['"BirthDate"'] = "\"${BirthDate.toUtc().toString().substring(0,19) }\"";
            }
                    if( DateTimeArr != null ){
                        _json['"DateTimeArr"'] = new List<dynamic>.from(DateTimeArr.map((x) => x.toUtc().toString().substring(0,19))) ;
            }
        
        return _json;

    }

    Map<String, dynamic> toMap(){
        Map<String, dynamic> _json = Map<String, dynamic>();

                    if( ArticleId != null ){
                _json["ArticleId"] = ArticleId;            }
                    if( Books != null ){
                _json["Books"] = new List<dynamic>.from(Books.map((x) => x.toMap())) ;            }
                    if( Nicknames != null ){
                _json["Nicknames"] = new List<dynamic>.from(Nicknames) ;            }
                    if( BirthDate != null ){
                _json["BirthDate"] = BirthDate.toUtc().toString().substring(0,19) ;            }
                    if( DateTimeArr != null ){
                _json["DateTimeArr"] = new List<dynamic>.from(DateTimeArr.map((x) => x.toUtc().toString().substring(0,19))) ;            }
        
        return _json;

    }


    }