jQuery(function(){
	let root=$('.image-upload-wrapper');
	// операция загрузки файлов ...
	root.on('change','input#inputfile',(e)=>{
		e.preventDefault();		
		//console.log(e.target.files,'dsa');
		let form=new FormData();
		form.append('file-uploader',1);
		let co=0;
		for(let i=0;i<e.target.files.length;i++){
			let f=e.target.files.item(i);

			if (f.size>1024*1024){
				alert('Загрузка картинок больше 1Мб не допустима');
				return;
			}

			form.append('files[]',f,f.name);
			co++;
		}
		if (co)
			$.ajax({
				type:'post',
				url:'',
				data:form,
				processData:false,
				contentType:false,
			}).done(function(ret){
				root.trigger('reload-imgs');
			});
			
	});
	// обновление списка загруженнных картинок ...
	root.on('reload-imgs',(ret)=>{
		$.post('',{'get-imgs-for':root.data('id')},(ret)=>{
			let imgPlace=root.find('.img-places');
			imgPlace.html('');
			if (!ret.list)
				return;

			for(let i=0;i<ret.list.length;i++){
				let el=$('<label class="img-el">');
				let img=$('<img>');
				img.attr({
					src:ret.list[i].url,
					width:200,
					height:200,
				});
				el.append(img);
				//активизация/деактивизация каринки .. 
				let check=$('<input>');
				check.attr({
					type:'checkbox',
					title:'Активный элемент',
				});
				check.data('img',ret.list[i].url);
				if (ret.list[i].active)
					check.attr('checked',true);
				check.on('change',(e)=>{
					// снять выделение ...
					imgPlace.find(':input').each((ind,el)=>{
						if (el!=e.target)
							el.checked=false;
					});
					$.post('',{'set-img-status':$(e.target).data('img'),curst:e.target.checked-0,});
					
				});
				el.append(check);
				// кнопка удаление картинки ..
				let killer=$('<input/>');
				killer.attr({
					type:"button", 
					class:"killer", 
					value:"Х",
					title:'Удалить',
				});
				killer.data('img',ret.list[i].url);
				killer.on('click',(e)=>{
					$.post('',{'kill-img':$(e.target).data('img',)},(ret)=>{
						if(ret.ok)
							root.trigger('reload-imgs');
					});
				});
				el.append(killer);
				imgPlace.append(el);
			}
			
		});
	}).trigger('reload-imgs');
});