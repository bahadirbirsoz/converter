
import 'package:tms_mobile/entities/Writer.dart';
export 'package:tms_mobile/entities/Writer.dart';


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

    Map<String, dynamic> toJson(){
        Map<String, dynamic> _json = Map<String, dynamic>();

                    if( ArticleId != null ){
                        _json['"ArticleId"'] = "\"${ArticleId}\"";
            }
                    if( Title != null ){
                        _json['"Title"'] = "\"${Title}\"";
            }
                    if( Price != null ){
                        _json['"Price"'] = "\"${Price}\"";
            }
                    if( Writer != null ){
                        _json['"Writer"'] = Writer.toJson();
            }
                    if( ReleaseTime != null ){
                        _json['"ReleaseTime"'] = "\"${ReleaseTime.toUtc().toString().substring(0,19) }\"";
            }
        
        return _json;

    }

    Map<String, dynamic> toMap(){
        Map<String, dynamic> _json = Map<String, dynamic>();

                    if( ArticleId != null ){
                _json["ArticleId"] = ArticleId;            }
                    if( Title != null ){
                _json["Title"] = Title;            }
                    if( Price != null ){
                _json["Price"] = Price;            }
                    if( Writer != null ){
                _json["Writer"] = Writer.toMap();            }
                    if( ReleaseTime != null ){
                _json["ReleaseTime"] = ReleaseTime.toUtc().toString().substring(0,19) ;            }
        
        return _json;

    }


    }