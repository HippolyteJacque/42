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

	private MayaMove MayaS;

	// Use this for initialization
	void Start () {
		MayaS = GetComponent<MayaMove>();
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
		XPValue.text = Mathf.RoundToInt(MayaS.XP).ToString();
		XPNextLevelValue.text = MayaS.xpNextLvl.ToString();
		LevelValue.text = MayaS.Level.ToString();
		StrenghValue.text = MayaS.STR.ToString();
		AgilityValue.text = MayaS.AGI.ToString();
		ConstitutionValue.text = MayaS.CON.ToString();
		HPValue.text = MayaS.maxHP.ToString();
		CreditsValue.text = MayaS.Credits.ToString();
		minDMGValue.text = Mathf.RoundToInt(MayaS.minDamage).ToString();
		maxDMGValue.text = Mathf.RoundToInt(MayaS.maxDamage).ToString();
		ArmorValue.text = MayaS.Armor.ToString();
		if (MayaS.Points > 0){
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
