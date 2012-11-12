var chooser;


/*美达用户管理菜单
*/
ModaUserPanel=function()
{	
	ModaUserPanel.superclass.constructor.call(this, {
        autoScroll:true,
        animate:true,
        border:false,
        rootVisible:false,
        root:new Ext.tree.TreeNode({
        text: '用户管理菜单',
        draggable:false,
       	expanded:true
   	 })        
    });
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:'新用户申请',
		icon:'./extjsAdmin/images/20070417191607553.gif',
   		listeners:{'click':function(){
			var panel=Ext.getCmp("userListPanel");
			if(!panel){
					panel=new UserListManage();
					editTopic=function(id)
					{
						var record=panel.grid.getSelectionModel().getSelected();
						if(!record){
							Ext.Msg.alert("提示","请先选择要编辑的行!");
							return;
						}
						else
						{
							Ext.Msg.alert("提示","请先选择要编辑的行!");
							return;
							}
					};
				}
				
				showDetail=function (){
					var record=panel.grid.getSelectionModel().getSelected();
					if(!record){
						Ext.Msg.alert("提示","请先选择要编辑的行!");
						return;
					}
					else
					{
							if(!chooser){
								chooser = new ImageChooser({
									url:'?Controller=ExtjsAdmin&action=imglist&user_id='+record.get("user_id"),
									width:800,
									height:700
								});
							}
							chooser.show('?Controller=ExtjsAdmin&action=imglist&user_id='+record.get("user_id"));

					}
				};
				
			main.openTab(panel);

   		}}	
   		})); 
   
   
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"未通过审核用户",
		icon:'./extjsAdmin/images/20070417191606608.gif',
   		listeners:{'click':function(){

			var panel=Ext.getCmp("nopasslistmanage");
			if(!panel){
					panel=new NoPassListManage();
			}		
			showDetail=function (){
				var record=panel.grid.getSelectionModel().getSelected();
				if(!record){
					Ext.Msg.alert("提示","请先选择要编辑的行!");
					return;
				}
				else
				{
						if(!chooser){
							chooser = new ImageChooser({
								width:800,
								height:700
							});
						}
						chooser.show('?Controller=ExtjsAdmin&action=imglist&user_id='+record.get("user_id"));

				}
			};
					
			main.openTab(panel);
			
				
   			}}	
   		})); 
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"会员管理",
		icon:'./extjsAdmin/images/20070417191603307.gif',
   		listeners:{'click':function(){
			
			var panel=Ext.getCmp("ModaUserListPanel");
			if(!panel){
					panel=new ModaUserListManage();
					on_userInfo=function (){
						
						var record=panel.grid.getSelectionModel().getSelected();
						if(!record){
							Ext.Msg.alert("提示","请先选择要编辑的行!");
							return;
						}
						else
						{
							perInfo(record,panel.store);
							return;
						}
					};	
					
			}		
			main.openTab(panel);
			
			
   			}}	
   		})); 
   
}
Ext.extend(ModaUserPanel, Ext.tree.TreePanel,{});



/*新用户注册申请
*/
UserListManage=Ext.extend(GaoP.Ext.CrudPanel,{
						  
	id:"userListPanel",
	title:"新用户注册申请",

	showHeight:function (value) {
		return value+"/CM";
		
	},
	
	showImage:function (value) {
		
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		//return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value.replace(".","_mini_.")+'" ></div></div>';
	},
		
    operationRender:function(obj){
		return !obj||obj=="-1"?"":"<a href='javascript:showDetail()'>[详图]</a>";
		
	}, 
	
	
	showDate:function (value) {
		
		 var dat = new Date(parseInt(value) * 1000);
		 
		 var m = dat.getMonth();
		 m++;
		 
		 var time = dat.getFullYear()+"-"+m+"-"+dat.getDate();
		 
   		 return time;
	},
	
	nopass:function (value) 
	{
				var record=this.grid.getSelectionModel().getSelected();
				if(!record){
					Ext.Msg.alert("提示","请先选择要编辑的行");
					return;
				}
			  Ext.Ajax.request({
					 url: '?Controller=ExtjsAdmin&action=NoAgree',
					  params: {
						  user_id : record.get("user_id")
					  },
					 success:function(request){                   //发送成功的回调函数
						 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
						 Ext.Msg.alert('信息',message);        //弹出对话框
					}
			  });
			  this.store.reload();
	},
	passto:function (value) {
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}

		store = this.store;
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "我爱美达")
				{
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=Agree',
							  params: {
								  user_id : record.get("user_id")
							  },
							 success:function(request){                   //发送成功的回调函数
								 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
								 Ext.Msg.alert('信息',message);        //弹出对话框
								 store.reload();
							}
					  });
						
					}
				else
				{
					Ext.Msg.alert('SORRY!', '口令错误');
					}
			}
		});


	},
	
	edit:function()
	{
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}
		else
		{
					showDetail();
					return;
			}
	},
	sendmsg:function()
	{
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}
		else
		{
					sendmsgwind(record);
					return;
			}
	},
	
	
	storeMapping:["user_id","dateline","errormes","username","nickname","truename","birthdate","mobile","qq","height","weight","img1","img2","img3","img4","uid","msg_b","e_msg",{name:"op",mapping:"user_id"}
	],	
	
    initComponent : function(){
		
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=NewUserList',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
		
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "申请时间",
			   dataIndex: 'dateline',
			   width:50,
			   renderer: this.showDate
			},{
			   header: "用户名",
			   dataIndex: 'username',
			   width: 50
			},{
			   header: "昵称",
			   dataIndex: 'nickname',
			   width: 40
			},{
			   header: "姓名",
			   dataIndex: 'truename',
			   width: 40
			},{
			   header: "职业",
			   dataIndex: 'msg_b',
			   width: 40
			},{
			   header: "自述",
			   dataIndex: 'e_msg',
			   width: 40
			},{
			   header: "出生日期",
			   dataIndex: 'birthdate',
			   width: 50
			},{
			   header: "联系电话",
			   dataIndex: 'mobile',
			   width: 50
			},{
			   header: "QQ号",
			   dataIndex: 'qq',
			   width: 50,
			   align: 'center'
			},{
			   header: "身高",
			   dataIndex: 'height',
			   width: 30,
			   renderer: this.showHeight
			},{
			   header: "体重(KG)",
			   dataIndex: 'weight',
			   width: 40,
			   renderer: this.showWeight
			},{
			   header: "照片1",
			   dataIndex: 'img1',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片2",
			   dataIndex: 'img2',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片3",
			   dataIndex: 'img3',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片4",
			   dataIndex: 'img4',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},
			{
				header: "操作", 
				sortable:false,
			    align: 'center',
				width: 30, 
				dataIndex:"op",
				renderer:this.operationRender
			}
		]);  
	
		var s_pannel = new Ext.FormPanel({
			height: 20,
			labelWidth: 50,
			baseCls: 'x-plain',
			defaults: {
				width: 150,
				height: 10
			},
			defaultType: 'textfield',
			items: [{
				fieldLabel: 'Search',
				id: 'title',
				name: 'title',
				allowBlank: false,//禁止为空
				blankText: '请输入用户名'
			}]
		});
	
		this.topbar = ['   ',
				 {    
					text: '添加荔枝用户',  
					pressed: true,           
					handler: function(){
							var panel=Ext.getCmp("userListPanel");
							addNewModaUser(panel.store);
						},
					scope:this
				},'   ',
				{    
					text: '待审核',  
					pressed: true,            
					handler: this.nopass,
					scope:this
				},'   ',
				{    
					text: '通过',  
					pressed: true,           
					handler: this.passto,
					scope:this
				},'   ',
				 {    
					text: '刷新',  
					pressed: true,           
					handler: function(){
							this.store.reload({
								params: {
								}
							});
					},
					scope:this
				},'   ',
				 {    
					text: '发送论坛短消息',  
					pressed: true,           
					handler: this.sendmsg,
					scope:this
				},
				new Ext.Toolbar.Fill(),
				s_pannel,				
//				{    
//					xtype:"textfield",
//					blankText: '搜索内容不能为空',
//					width:100,
//					scope:this
//				},'   ',
				{    
					text: '查询',   
					pressed: true,           
					handler: function(){
						// 这里是关键，重新载入数据源，并把搜索表单值提交
						this.store.reload({
							params: {
								start: 0,
								limit: 10,
								title: Ext.get('title').dom.value//取得搜索表单文本域的值，发送到服务端
							}
						})
				   },
					scope:this
				},'   '
			];	
	
	
		UserListManage.superclass.initComponent.call(this);
	}
});


/*未通过用户
*/
NoPassListManage=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"nopasslistmanage",
	title:"未通审核过用户",


	showHeight:function (value) {
		return value+"/CM";
		
	},
	
	showImage:function (value) {
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		
	},
		
    operationRender:function(obj){
		return !obj||obj=="-1"?"":"<a href='javascript:showDetail()'>[详图]</a>";
		
	}, 
	showDate:function (value) {
		
		 var dat = new Date(parseInt(value) * 1000);
		 
		 var m = dat.getMonth();
		 m++;
		 
		 var time = dat.getFullYear()+"-"+m+"-"+dat.getDate();
		 
   		 return time;
	},
	
	passto:function (value) {
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}
		store = this.store;
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "我爱美达")
				{
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=Agree',
							  params: {
								  user_id : record.get("user_id")
							  },
							 success:function(request){                   //发送成功的回调函数
								 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
								 Ext.Msg.alert('信息',message);        //弹出对话框
								 store.reload();
							}
					  });
					  
					}
				else
				{
					Ext.Msg.alert('SORRY!', '口令错误');
					}
			}
		});
	},
	deleteto:function (value) {
		 Ext.Msg.alert("提示","未了数据的丰富，请不要删除数据");
	},
	sendmsg:function()
	{
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}
		else
		{
					sendmsgwind(record);
					return;
			}
	},

	storeMapping:["user_id","dateline","username","nickname","truename","birthdate","mobile","qq","height","weight","img1","img2","img3","img4","uid","msg_b","e_msg",{name:"op",mapping:"user_id"}
	],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=GetListNopass',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "注册时间",
			   dataIndex: 'dateline',
			   width:50,
			   renderer: this.showDate
			},{
			   header: "用户名",
			   dataIndex: 'username',
			   width: 50
			},{
			   header: "昵称",
			   dataIndex: 'nickname',
			   width: 50
			},{
			   header: "姓名",
			   dataIndex: 'truename',
			   width: 50
			},{
			   header: "职业",
			   dataIndex: 'msg_b',
			   width: 50
			},{
			   header: "自述",
			   dataIndex: 'e_msg',
			   width: 50
			},{
			   header: "出生日期",
			   dataIndex: 'birthdate',
			   width: 50
			},{
			   header: "联系电话",
			   dataIndex: 'mobile',
			   width: 60
			},{
			   header: "QQ号",
			   dataIndex: 'qq',
			   width: 50,
			   align: 'center'
			},{
			   header: "身高",
			   dataIndex: 'height',
			   width: 50,
			   renderer: this.showHeight
			},{
			   header: "体重(KG)",
			   dataIndex: 'weight',
			   width: 50,
			   renderer: this.showWeight
			},{
			   header: "照片1",
			   dataIndex: 'img1',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片2",
			   dataIndex: 'img2',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片3",
			   dataIndex: 'img3',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},{
			   header: "照片4",
			   dataIndex: 'img4',
			   width:70,
			   align: 'center',
			   renderer: this.showImage
			},
			{
				header: "操作", 
				sortable:false,
			    align: 'center',
				width: 30, 
				dataIndex:"op",
				renderer:this.operationRender}
		]);  
	
	
		this.topbar = ['   ',
				 {    
					text: '通过',  
					pressed: true,           
					handler: this.passto,
					scope:this
				},'   ',
				{    
					text: '删除',  
					pressed: true,           
					handler: this.deleteto,
					scope:this
				},'   ',
				 {    
					text: '刷新',  
					pressed: true,           
					handler: function(){
							this.store.reload({
								params: {
								}
							});
					},
					scope:this
				},'   ',
				 {    
					text: '发送论坛短消息',  
					pressed: true,           
					handler: this.sendmsg,
					scope:this
				}
			];
	
	
		NoPassListManage.superclass.initComponent.call(this);
	}

	});


/*美达用户管理
*/
ModaUserListManage=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"ModaUserListPanel",
	title:"会员管理",



	showHeight:function (value) {
		return value+"/CM";
		
	},
	showstatus:function (value) 
	{
		if(value == 1)
			return "荔枝先生";
		else
			return "会员";
	},
	
	showImage:function (value) {
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		
	},
		
    operationRender:function(obj){
		return !obj||obj=="-1"?"":"<a href='javascript:showDetail()'>[详图]</a>";
		
	}, 
	
	
	showDate:function (value) {
		
		 var dat = new Date(parseInt(value) * 1000);
		 
		 var m = dat.getMonth();
		 m++;
		 
		 var time = dat.getFullYear()+"-"+m+"-"+dat.getDate();
		 
   		 return time;
	},
	nopass:function (value) {
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","aaa");
			return;
		}
		store = this.store;
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "我爱美达")
				{
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=NoAgree',
							  params: {
								  user_id : record.get("user_id")
							  },
							 success:function(request){                   //发送成功的回调函数
								 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
								 Ext.Msg.alert('信息',message);        //弹出对话框
					  			 store.reload();
							}
					  });
						
					}
				else
				{
					Ext.Msg.alert('SORRY!', '口令错误');
					}
			}
		});
		
		 
	},
    perinfo:function(obj){
			return !obj||obj=="-1"?"":"<a href='javascript:on_userInfo("+obj+")'>[查看个人资料]</a>";
	}, 
    modaInfo:function(obj){
		
			var record=this.grid.getSelectionModel().getSelected();
			if(!record){
				Ext.Msg.alert("提示","请先选择要编辑的行!");
				return;
			}
			else
			{
				modaUserInfo(record,this.store);
				return;
			}

	}, 
    modaerShowsManage:function(obj){
		
			var record=this.grid.getSelectionModel().getSelected();
			if(!record){
				Ext.Msg.alert("提示","请先选择要编辑的行!");
				return;
			}
			else
			{
				ModaerShowManage(record.get("user_id"),record.get("truename"));
				return;
			}

	}, 
    PersonalPageImgs:function(obj){
		
			var record=this.grid.getSelectionModel().getSelected();
			if(!record){
				Ext.Msg.alert("提示","请先选择要编辑的行!");
				return;
			}
			else
			{
									//window.showModalDialog('?Controller=ExtjsAdmin&action=PersonalPageImgs&user_id='+record.get("user_id"),'PersonalPageImgs','dialogWidth:880px;dialogHeight:610px;center:yes;help:yes;resizable:yes;status:yes');
							window.open('?Controller=ExtjsAdmin&action=PersonalPageImgs&user_id='+record.get("user_id"),'PersonalPageImgs','PersonalPageImgs',"width=880,height=610")

				return;
			}

	}, 
	
    advanceSerch:function(obj)
	{
			SerchVindow(this.store);
	}, 
	sendmsg:function()
	{
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行");
			return;
		}
		else
		{
					sendmsgwind(record);
					return;
			}
	},
	
	storeMapping:["user_id","truename","restime","lastvisit","reallpageview","pageviews","showcount","att_count","dis_count","club_count","clubcall_count","showurl",{name:"op",mapping:"user_id"},"username","nickname","birthdate","mobile","qq","height","weight","show_id","email","active_num","status","head_img","uid"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=UserInfo',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			
			
			{        
			   header: "姓名",
			   dataIndex: 'truename',
			   width:50
			},{
			   header: "荔枝ID",
			   dataIndex: 'user_id',
			   width:50
			},{
			   header: "注册时间",
			   dataIndex: 'restime',
			   width:50
			},{
			   header: "最近登录时间",
			   dataIndex: 'lastvisit',
			   width: 50
			},{
			   header: "展示总浏览量",
			   dataIndex: 'reallpageview',
			   width: 50
			},{
			   header: "页面显示浏览量",
			   dataIndex: 'pageviews',
			   width: 50
			},{
			   header: "展示数量",
			   dataIndex: 'showcount',
			   width: 50
			},{
			   header: "展示总图片量",
			   dataIndex: 'att_count',
			   width: 50
			},{
			   header: "展示总评论量",
			   dataIndex: 'dis_count',
			   width: 50
			},{
			   header: "活跃数",
			   dataIndex: 'active_num',
			   width: 50
			},{
			   header: "头像",
			   dataIndex: 'head_img',
			   width: 50,
			   renderer: this.showImage
			},{
			   header: "状态",
			   dataIndex: 'status',
			   width: 50,
			   renderer: this.showstatus
			},
			{
				header: "操作", 
				sortable:false,
			    align: 'center',
				width: 70, 
				dataIndex:"op",
				renderer:this.perinfo
				}
		]);  
		this.topbar = ['   ',
				{    
					text: '展示管理',  
					pressed: true,            
					handler: this.modaerShowsManage,
					scope:this
				},'   ',				 
				{    
					text: '图片轮换',  
					pressed: true,            
					handler: this.PersonalPageImgs,
					scope:this
				},'   ',				 
				{    
					text: '修改',  
					pressed: true,           
					handler: this.modaInfo,
					scope:this
				},'   ',
				{    
					text: '待审核',  
					pressed: true,           
					handler: this.nopass,
					scope:this
				},'   ',
				 {    
					text: '刷新',  
					pressed: true,           
					handler: function(){
							this.store.reload({
								params: {
								}
							});
					},
					scope:this
				},'   ',
				 {    
					text: '发送论坛短消息',  
					pressed: true,           
					handler: this.sendmsg,
					scope:this
				 }
				,new Ext.Toolbar.Fill(),
				 {    
					text: '高级查询',  
					pressed: true,           
					handler: this.advanceSerch,
					scope:this
				}
				];
	
	
		ModaUserListManage.superclass.initComponent.call(this);
	}

	});


