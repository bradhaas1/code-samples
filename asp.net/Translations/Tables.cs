using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Xml;
using System.Data;
using System.Xml.Linq;
using System.Net;
using System.IO;

namespace Translations
{
   class Tables
   {

      public static DataSet GetSapDataSet()
      {
         DataSet ds = new DataSet();
         
         ds.Tables.Add("Categories");
         ds.Tables["Categories"].Columns.Add("Category");

         ds.Tables.Add("Items");
         ds.Tables["Items"].Columns.Add("Sku");
         ds.Tables["Items"].Columns.Add("Name");
         ds.Tables["Items"].Columns.Add("CategoryCode");
         ds.Tables["Items"].Columns.Add("CategoryName");

         string sapUrl = "http://192.168.10.25:4033/query/devGetAllItemsWName.aspx";
         XDocument xdoc = ConnectToSap(sapUrl);
         foreach (XElement xe in xdoc.Descendants("row"))
         {
            string itemcode = (xe.Element("OITB_ItmsGrpNam").Value);

            DataRow rowNew = ds.Tables["Categories"].NewRow();
            rowNew["Category"] = itemcode;
            ds.Tables["Categories"].Rows.Add(rowNew);
         };

         foreach (XElement xe in xdoc.Descendants("row"))
         {
            // Get values from XMLand fill vars
            string itemcode = (xe.Element("OITM_ItemCode").Value);
            string itemname = (xe.Element("OITM_ItemName").Value);
            string itemcategorycode = (xe.Element("OITB_ItmsGrpCod").Value);
            string itemcategoryname = (xe.Element("OITB_ItmsGrpNam").Value);


            DataRow rowNew = ds.Tables["Items"].NewRow();
            rowNew["Sku"] = itemcode;
            rowNew["Name"] = itemname;
            rowNew["CategoryCode"] = itemcategorycode;
            rowNew["CategoryName"] = itemcategoryname;

            ds.Tables["Items"].Rows.Add(rowNew);
         }
         return ds;
      }

      public static DataSet GetProducts()
      {
         string sapUrl = "http://192.168.10.25:4033/query/devGetAllItemsWName.aspx";
         XDocument xdoc = ConnectToSap(sapUrl);

         DataTable dt = new DataTable();

         DataSet ds = new DataSet();
         ds.Tables.Add("Items");

         ds.Tables["Items"].Columns.Add("Sku");
         ds.Tables["Items"].Columns.Add("Name");

         foreach (XElement xe in xdoc.Descendants("row"))
         {
            // Get values from XMLand fill vars
            string itemcode = (xe.Element("OITM_ItemCode").Value);
            string itemname = (xe.Element("OITM_ItemName").Value);
            DataRow rowNew = ds.Tables["Items"].NewRow();
            rowNew["Sku"] = itemcode;
            rowNew["Name"] = itemname;

            ds.Tables["Items"].Rows.Add(rowNew);
         }

         return ds;
      }

      public static XDocument ConnectToSap(string url)
      {

         string userName = "test";
         string passWord = "test";

         string relativeUrl = url;

         HttpWebRequest request = (HttpWebRequest)WebRequest.Create(relativeUrl);
         request.Credentials = new NetworkCredential(userName, passWord);
         request.Method = WebRequestMethods.Http.Get;
         string credentials = Convert.ToBase64String(ASCIIEncoding.ASCII.GetBytes("test" + ":" + "test"));
         request.Headers.Add("Authorization", "Basic " + credentials);

         HttpWebResponse response = (HttpWebResponse)request.GetResponse();
         // Gets the stream associated with the response.
         Stream receiveStream = response.GetResponseStream();

         Encoding encode = Encoding.GetEncoding("utf-8");
         // Pipes the stream to a higher level stream reader with the required encoding format. 
         StreamReader readStream = new StreamReader(receiveStream, encode);
         string xml = readStream.ReadToEnd();
         XDocument xdoc = XDocument.Parse(xml);

         return xdoc;
      }
   }
}
