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
using System.Runtime;
using System.Collections;
using System.Collections.Generic;

namespace Translations.VisualWebPart1
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
            
           //ArrayList uom = new ArrayList();
           // uom.Add("Inches");
           // uom.Add("Centimeters");

           // ArrayList uow = new ArrayList();
           // uow.Add("lb");
           // uow.Add("kg");

           // measurement.DataSource = uom;
           // measurement.DataBind();

           // weight.DataSource = uow;
           // weight.DataBind();          
        }

        public void Page_Load(object sender, EventArgs e)
        {
           if (!Page.IsPostBack)
           {
              GetLanguages();
              CategoryDropDownList();
              Header.Text = LanguageDDL.SelectedValue + " " + ItemDDL.SelectedValue;
           }
        }

        public void GetLanguages()
        {
           ProjectManagementDataContext dc = new ProjectManagementDataContext(SPContext.Current.Web.Url);
           EntityList<LanguagesItem> Languages = dc.GetList<LanguagesItem>("Languages");
           
           DataSet ds = new DataSet();
           ds.Tables.Add("Language Name");
           ds.Tables["Language Name"].Columns.Add("Language");

           var query = from language in dc.GetList<LanguagesItem>("Languages")
                       select language.Title;
               foreach (var name in query) {
                  //Label1.Text += name;

                  DataRow rowNew = ds.Tables["Language Name"].NewRow();
                  rowNew["Language"] = name;
                  ds.Tables["Language Name"].Rows.Add(rowNew);
               }

               LanguageDDL.DataSource = ds.Tables["Language Name"];
               LanguageDDL.DataTextField = "Language";
               LanguageDDL.DataBind();
        }

        public void CategoryDropDownList()
        {
            DataSet sds = Tables.GetSapDataSet();
            DataView catView = new DataView(sds.Tables["Items"]);
            catView.Sort = "CategoryName ASC";
            DataTable catTable = catView.ToTable(true, "CategoryName");

            CategoryDDL.DataSource = catTable;
            CategoryDDL.DataTextField = "CategoryName";
            CategoryDDL.DataBind();
        }

        protected void CategoryDDL_SelectedIndexChanged(object sender, EventArgs e)
        {
           //string value = CategoryDDL.SelectedValue;
           //Message.Text = value;

           DataSet sds = Tables.GetSapDataSet();
           DataView itemsView = new DataView(sds.Tables["Items"]);
           itemsView.RowFilter = "CategoryName = '" + CategoryDDL.SelectedValue + "'";
           DataTable itemTable = itemsView.ToTable("ItemNames", true, "Name");
           ItemDDL.DataSource = itemTable;
           ItemDDL.DataTextField = "Name";
           ItemDDL.DataBind();
           Header.Text = LanguageDDL.SelectedValue + " " + ItemDDL.SelectedValue;
        }

        protected void InsertTranslation()
        {
           ProjectManagementDataContext dc = new ProjectManagementDataContext(SPContext.Current.Web.Url);
           EntityList<TranslationsItem> Translations = dc.GetList<TranslationsItem>("Translations");
           string language, item, category, description, features;
           language = LanguageDDL.SelectedValue;
           category = CategoryDDL.SelectedValue;
           item = ItemDDL.SelectedValue;
           description = ItemDescription.Text;
           features = ItemFeatures.Text;
           TranslationsItem translation = new TranslationsItem()
           {
            Language = language,
            Category = category,
            Item0 = item,
            Description = description,
            Features = features
           };

           Translations.InsertOnSubmit(translation);
           dc.SubmitChanges();

           ItemDescription.Text = "";
           ItemFeatures.Text = "";
 
           CategoryDropDownList();
         }

        protected void Insert_Click(object sender, EventArgs e)
        {
           InsertTranslation();
        }

        protected void View_Click(object sender, EventArgs e)
        {
           ProjectManagementDataContext dc = new ProjectManagementDataContext(SPContext.Current.Web.Url);
           EntityList<TranslationsItem> Translations = dc.GetList<TranslationsItem>("Translations");

           string itemname = LanguageDDL.SelectedValue + " - " + ItemDDL.SelectedValue;
           var query = from translation in Translations
                       where (Convert.ToString(translation.Name)) == itemname
                       select new { description = translation.Description, features = translation.Features };

           //ItemDescription.Text = myitem;
           if (query != null )
              if (query.Any())
           {
              ItemDescription.Text = query.First().description;
              ItemFeatures.Text = query.First().features;
           }
        }

        protected void ItemDDL_SelectedIndexChanged(object sender, EventArgs e)
        {
           DataSet sds = Tables.GetSapDataSet();
           DataView itemsView = new DataView(sds.Tables["Items"]);
           Header.Text = LanguageDDL.SelectedValue + " " + ItemDDL.SelectedValue;

        }
















    }
}



