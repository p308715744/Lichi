

/*美达用户管理菜单
*/
ModaIndexPanel=function()
{	


	ModaIndexPanel.superclass.constructor.call(this, {
        autoScroll:true,
        animate:true,
        border:false,
        rootVisible:false,
        root:new Ext.tree.TreeNode({
        text: '首页',
        draggable:false,
       	expanded:true
   	 })        
    });
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"首页管理",
   		listeners:{'click':function()
		{
					var panel=Ext.getCmp("ModaConfig");
					if(!panel)
					{
							panel=new ModaConfig();
					}
					main.openTab(panel);
   		}}	
   		})); 
   
}
Ext.extend(ModaIndexPanel, Ext.tree.TreePanel,{});


/**
 */
ModaConfig=Ext.extend(Ext.Panel,{
	id:"ModaConfig",
	title:"首页管理",
	closable: true,
  	autoScroll:true,
  	layout:"fit",  			
	save:function()
	{	
			if (this.getComponent('rank_form_add').form.isValid()) 
			{
						this.fp.form.submit({
								waitMsg:'正在保存。。。',
								url:"?Controller=ExtjsAdmin&action=IndexPageSet",
								method:'POST',
								success:function(form, action){
										 Ext.MessageBox.alert('提示', action.result.message);
							   },
								failure : function(form, action) {
										 Ext.MessageBox.alert('提示', action.result.message);
									},
								scope:this
						});
			}
			else
			{
						Ext.MessageBox.alert('警告', "失败");
				}
	},
	createFormPanel:function()
	{
		return  new Ext.form.FormPanel({
									   
			id: 'rank_form_add',
			name: 'rank_form_add',
			buttonAlign:"center",
			labelAlign:"right",
			bodyStyle:'padding:25px',
			defaults:{width:650},
			frame:false,
			fileUpload:true,
			bodyBorder:false,
			border:true,
			labelWidth:60,
			autoHeight:true,

items: [{
        layout:'column',   //定义该元素为布局为列布局方式
        border:false,
        labelSeparator:'：',
		
        items:[{
            columnWidth:.5,  //该列占用的宽度，标识为50％
            layout: 'form',
            border:false,
            items: [
			{  
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '轮换图片A',
                name: 'img_1',
            },
			{  
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '轮换图片B',
                name: 'img_2',
            },
			{  
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '轮换图片C',
                name: 'img_3',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '轮换图片D',
                name: 'img_4',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '右侧广告',
                name: 'img_5',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '荔枝通告图片',
                name: '1_img',
            },
			{ 
				height : 30,
                xtype:'textfield',
                fieldLabel: '荔枝MV',
                name: '2_img',
				emptyText: '不必填写',
				readOnly:true
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '赞助图片A',
                name: 'img_6',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '赞助图片B',
                name: 'img_7',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '赞助图片C',
                name: 'img_8',
            },
			{ 
				height : 30,
                xtype:'field',
	  			inputType:"file",
                fieldLabel: '赞助图片D',
                name: 'img_9',
            },
			]
        },{
            columnWidth:.5,
            layout: 'form',
            border:false,
			
            items: [
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img1_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img2_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img3_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img4_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img5_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'link_1img',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'MV的ID',
					name: 'link_2img',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img6_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img7_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img8_link',
					anchor:'90%'
				},
				{
					height : 30,
					xtype:'textfield',
					inputType:'textfield',
					fieldLabel: 'URL地址',
					name: 'img9_link',
					anchor:'90%'
				},
			
			]
        }]
		
    }],
				buttons:[{text:"提交",
  				  handler:this.save,
  				  scope:this},
  				  {text:"清空",
  				   handler:function(){this.fp.form.reset();},
  				   scope:this  				   
  				  },
  				  {text:"取消",
  				   handler:function(){Ext.getCmp("main").closeTab(this);},
  				   scope:this  				   
  				  }]
   	 });
   	 },
	initComponent : function(){
	
//var account_data = new Ext.data.Store({
//		    proxy: new Ext.data.HttpProxy({url:'?Controller=ExtjsAdmin&action=MainView'}),
//			reader: new Ext.data.JsonReader({},['username']),
//			remoteSort: false
//		});	
//		
//		Ext.MessageBox.alert('提示', account_data.indexOf());
		
		
		ModaConfig.superclass.initComponent.call(this);
		this.fp=this.createFormPanel();
		this.add(this.fp);	
	}
});
