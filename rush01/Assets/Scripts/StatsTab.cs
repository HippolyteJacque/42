using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class StatsTab : MonoBehaviour {

	public CanvasGroup StatsCanvas;
	public CanvasGroup GameHUD;
	public CanvasGroup EnemyCanvas;

	public Text XPValue;
	public Text XPNextLevelValue;
	public Text LevelValue;
	public Text StrenghValue;
	public Text AgilityValue;
	public Text ConstitutionValue;
	public Text HPValue;
	public Text CreditsValue;
	public Text minDMGValue;
	public Text maxDMGValue;
	public Text ArmorValue;

	public Button StrenghPlus;
	public Button AgilityPlus;
	public Button ConstitutionPlus;
	public Button CloseTab;

	// Use this for initialization
	void Start () {
		StatsCanvas.alpha = 0f;
	}
	
	// Update is called once per frame
	void Update () {
		if (Input.GetKeyDown("c")){
			Show();
		}
		if (StatsCanvas.alpha == 1f){
			UpdateValues();
		}
	}

	public void Show(){
		if (StatsCanvas.alpha == 0f){
			StatsCanvas.alpha = 1f;
			GameHUD.alpha = 0f;
			EnemyCanvas.alpha = 0f;
		}
		else{
			StatsCanvas.alpha = 0f;
			GameHUD.alpha = 1f;
		}
	}

	void UpdateValues(){
		XPValue.text = Mathf.RoundToInt(GetComponent<MayaMove>().XP).ToString();
		XPNextLevelValue.text = GetComponent<MayaMove>().xpNextLvl.ToString();
		LevelValue.text = GetComponent<MayaMove>().Level.ToString();
		StrenghValue.text = GetComponent<MayaMove>().STR.ToString();
		AgilityValue.text = GetComponent<MayaMove>().AGI.ToString();
		ConstitutionValue.text = GetComponent<MayaMove>().CON.ToString();
		HPValue.text = GetComponent<MayaMove>().maxHP.ToString();
		CreditsValue.text = GetComponent<MayaMove>().Credits.ToString();
		minDMGValue.text = Mathf.RoundToInt(GetComponent<MayaMove>().minDamage).ToString();
		maxDMGValue.text = Mathf.RoundToInt(GetComponent<MayaMove>().maxDamage).ToString();
		ArmorValue.text = GetComponent<MayaMove>().Armor.ToString();
		if (GetComponent<MayaMove>().Points > 0){
			StrenghPlus.interactable = true;
			AgilityPlus.interactable = true;
			ConstitutionPlus.interactable = true;
		}
		else {
			StrenghPlus.interactable = false;
			AgilityPlus.interactable = false;
			ConstitutionPlus.interactable = false;
		}
	}
}
