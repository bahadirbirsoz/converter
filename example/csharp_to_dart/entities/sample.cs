using Ovi.TMS.Data.Entity;

namespace Ovi.TMS.Data.DAO
{
    public class Article
    {
        public int ArticleId { get; set; }

        public string Title { get; set; }

        public decimal Price { get; set; }

        public Writer Writer { get; set; }

        public DateTime ReleaseTime { get; set; }
    }

    public class Writer
    {

        public int ArticleId { get; set; }

        public Book[] Books { get; set; }

        public List<string> Nicknames { get; set; }

        public Date BirthDate { get; set; }

        public DateTime[] DateTimeArr { get; set; }


    }
}