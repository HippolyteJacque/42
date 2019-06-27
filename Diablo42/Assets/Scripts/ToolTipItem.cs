using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ToolTipItem : MonoBehaviour {
	
	private ItemStats item;
	// Use this for initialization
	void Start () {
		item = GetComponent<ItemStats>();
	}
	
	void Update () {
	}

	void OnMouseOver() {
		DisplayItemTip();
	}
	void OnMouseExit() {
		HideItemTip();
	}

	public void DisplayItemTip(){
		if (item == null)
			item = GetComponent<ItemStats>();
		MayaMove.instance.itemToolTipCanvas.alpha = 1f;
		int type = item.type;
		string Type = "";
		if (type == 0)
			Type = "common";
		else if (type == 1)
			Type = "rare";
		else if (type == 2)
			Type = "epic";
		else if (type == 3)
			Type = "legendary";	
		MayaMove.instance.ToolTipContent.text = "Sword\n"+Type+"\ndmg : "+item.damage.ToString()+"\nattackspeed : "+item.attackSpeed.ToString();
	}
	public void HideItemTip(){
		MayaMove.instance.itemToolTipCanvas.alpha = 0f;
	}
}
