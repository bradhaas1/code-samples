using System;
using System.ComponentModel;
using System.Web.UI.WebControls.WebParts;
using System.Data;
using System.Xml;
using System.Web.UI.WebControls;
using System.Net;
using System.IO;
using System.Text;
using System.Linq;
using System.Xml.Linq;
using Microsoft.SharePoint;
using Microsoft.SharePoint.Linq;

namespace GetSapItemsVisualWebPart.VisualWebPart1
{
    [ToolboxItemAttribute(false)]
    public partial class VisualWebPart1 : WebPart
    {
        // Uncomment the following SecurityPermission attribute only when doing Performance Profiling using
        // the Instrumentation method, and then remove the SecurityPermission attribute when the code is ready
        // for production. Because the SecurityPermission attribute bypasses the security check for callers of
        // your constructor, it's not recommended for production purposes.
        // [System.Security.Permissions.SecurityPermission(System.Security.Permissions.SecurityAction.Assert, UnmanagedCode = true)]
        public VisualWebPart1()
        {
        }

        protected override void OnInit(EventArgs e)
        {
            base.OnInit(e);
            InitializeControl();
        }
        protected void Page_Load(object sender, EventArgs e)
        {
        }
        public void Button1_Click(object sender, EventArgs e)
        {
           getSapItems();
        }

        public void getSapItems()
        {
           string userName = "test";
           string passWord = "test";

           string relativeUrl = "http://192.168.10.25:4033/query/devGetAllItemsWName.aspx";

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
  
           // Create DataSet, Items table and columns
           DataSet products = new DataSet();
           products.Tables.Add("Items");

           products.Tables["Items"].Columns.Add("Sku");
           products.Tables["Items"].Columns.Add("Name");
           products.Tables["Items"].Columns.Add("Categories"); 

           foreach (XElement xe in xdoc.Descendants("row"))
           {
              // Get values from XMLand fill vars
              string itemcode = (xe.Element("OITM_ItemCode").Value);
              string itemname = (xe.Element("OITM_ItemName").Value);
              string itemcategory = (xe.Element("OITM_ItemName").Value);

              DataRow rowNew = products.Tables["Items"].NewRow();
              rowNew["Sku"] = itemcode;
              rowNew["Name"] = itemname;

              products.Tables["Items"].Rows.Add(rowNew);
           }
         
           DataGrid grid = new DataGrid();
           grid.DataSource = products;
           grid.DataBind();

           Controls.Add(grid);
           base.CreateChildControls();

           // Add Sharepoint context and entity
           ProjectManagementDataContext dc = new ProjectManagementDataContext(SPContext.Current.Web.Url);
           EntityList<ProductsItem> Products = dc.GetList<ProductsItem>("Products");


           // Add ProductsItem to List with loop
           foreach (XElement xe in xdoc.Descendants("row"))
           {

              string itemcode = (xe.Element("OITM_ItemCode").Value);
              string itemname = (xe.Element("OITM_ItemName").Value);
              
              ProductsItem productsToInsert = new ProductsItem(){
               Title = itemname,
               TextSku = itemcode
              };

              Products.InsertOnSubmit(productsToInsert);
               dc.SubmitChanges();
           }

           // Read from ProductsItem List

           var query = from product in Products select new { product.Title };

           GridView dg = new GridView();

           dg.DataSource = query;
           dg.DataBind();

           Controls.Add(dg);
           base.CreateChildControls();


        }

    }
}
