<%@ Assembly Name="$SharePoint.Project.AssemblyFullName$" %>
<%@ Assembly Name="Microsoft.Web.CommandUI, Version=14.0.0.0, Culture=neutral, PublicKeyToken=71e9bce111e9429c" %> 
<%@ Register Tagprefix="SharePoint" Namespace="Microsoft.SharePoint.WebControls" Assembly="Microsoft.SharePoint, Version=14.0.0.0, Culture=neutral, PublicKeyToken=71e9bce111e9429c" %> 
<%@ Register Tagprefix="Utilities" Namespace="Microsoft.SharePoint.Utilities" Assembly="Microsoft.SharePoint, Version=14.0.0.0, Culture=neutral, PublicKeyToken=71e9bce111e9429c" %>
<%@ Register Tagprefix="asp" Namespace="System.Web.UI" Assembly="System.Web.Extensions, Version=3.5.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35" %>
<%@ Import Namespace="Microsoft.SharePoint" %> 
<%@ Register Tagprefix="WebPartPages" Namespace="Microsoft.SharePoint.WebPartPages" Assembly="Microsoft.SharePoint, Version=14.0.0.0, Culture=neutral, PublicKeyToken=71e9bce111e9429c" %>
<%@ Control Language="C#" AutoEventWireup="true" CodeBehind="VisualWebPart1.ascx.cs" Inherits="Translations.VisualWebPart1.VisualWebPart1" %>

<asp:Panel ID="Dropdowns" runat="server" CssClass="selectors">
<asp:DropDownList ID="LanguageDDL" runat="server"></asp:DropDownList><br />
<asp:DropDownList ID="CategoryDDL" runat="server" AutoPostBack="true" OnSelectedIndexChanged="CategoryDDL_SelectedIndexChanged"></asp:DropDownList><br />
<asp:DropDownList ID="ItemDDL" runat="server" AutoPostBack="true" OnSelectedIndexChanged="ItemDDL_SelectedIndexChanged"></asp:DropDownList><br />
</asp:Panel>
<asp:Label ID="Message" runat="server"></asp:Label>

<asp:GridView ID="grid" runat="server"></asp:GridView>


<asp:Panel ID="Data" runat="server" CssClass="dataentry">
   <asp:Label ID="Header" runat="server" CssClass="pageheader"></asp:Label>
   <div style="clear:both;"></div>
   
   <div><h4>Description</h4>
   <asp:TextBox ID="ItemDescription" runat="server" CssClass="description" Rows="10" TextMode="MultiLine" Columns="40"></asp:TextBox>
   </div>
   <div><h4>Features and Benefits</h4>
   <asp:TextBox ID="ItemFeatures" runat="server" CssClass="features" Rows="10" TextMode="MultiLine" Columns="40"></asp:TextBox>
   </div>
   <div style="clear:both;"></div>

   <div class="specs"><h4>Short Description/Fashion</h4>
      <asp:TextBox ID="Inches" runat="server" class="specstextentry" Rows="3" TextMode="MultiLine" Columns="40"></asp:TextBox>
   </div>


   <div class="specs"><h4>Sku Designation</h4>
      <asp:TextBox ID="SkuDesignation" runat="server" class="specstextentry"></asp:TextBox>
   </div>

<%--   <div class="specs"><h4>Unit of Measurement</h4>
      <div class="split">
         <asp:CheckBoxList ID="measurement" runat="server" AutoPostBack="True" OnSelectedIndexChanged="measurement_SelectedIndexChanged"></asp:CheckBoxList>
      </div>
      <div class="split">
         <asp:TextBox ID="Inches" runat="server" class="specstextentry"></asp:TextBox><br />
         <asp:TextBox ID="Centimeters" runat="server" class="specstextentry"></asp:TextBox>
      </div>
   </div>
   
      
   <div class="specs"><h4>Unit of Weight</h4>
      <div class="split">
         <asp:CheckBoxList ID="weight" runat="server" OnSelectedIndexChanged="weight_SelectedIndexChanged"></asp:CheckBoxList>
      </div>
      <div class="split">
         <asp:TextBox ID="Pounds" runat="server" class="specstextentry"></asp:TextBox><br />
       <asp:TextBox ID="Kilograms" runat="server" class="specstextentry"></asp:TextBox>
   </div>--%>

<div style="clear:both;"></div>

<div class="controls">
   <asp:Button ID="Insert" runat="server" Text ="Insert" OnClick="Insert_Click" CssClass="submit" />
   <asp:Button ID="View" runat="server" Text ="View" OnClick="View_Click" CssClass="submit" />
</div>
   


</asp:Panel>