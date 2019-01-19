using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Events;

public class SpellsTab : MonoBehaviour {

	public static SpellsTab instance; 
	public CanvasGroup SpellsCanvas;
	public CanvasGroup GameHUD;
	public CanvasGroup EnemyCanvas;

	public Button FireballPlus;
	public Button HealPlus;
	public Button BurstballPlus;
	public Button FirewalkPlus;
	public Button FirearmorPlus;
	public Button FirespikePlus;

	public Text FireballValue;
	public Text HealValue;
	public Text BurstballValue;
	public Text FirewalkValue;
	public Text FirearmorValue;
	public Text FirespikeValue;

	public int FireballLvl;
	public int HealLvl;
	public int BurstballLvl;
	public int FirewalkLvl;
	public int FirearmorLvl;
	public int FirespikeLvl;

	public MayaMove MayaS;

	void Awake()
	{
		if (instance != null)
			Destroy(instance.gameObject);
		instance = this;
	}

	public SingleTargetSpell fireball;
	public Spell aoeSpell;
	public FireShield fireShield;
	public HealSpell healSpell;

	public UnityEvent spell1;
	public UnityEvent spell2;
	public UnityEvent spell3;
	public UnityEvent spell4;
	void Start () {
		fireball = FindObjectOfType<SingleTargetSpell>();
		aoeSpell = FindObjectOfType<Spell>();
		fireShield = FindObjectOfType<FireShield>();
		healSpell = FindObjectOfType<HealSpell>();

		// fireball.addSpell(1);
		// aoeSpell.addSpell(2);
		// fireShield.addSpell(3);
		// healSpell.addSpell(4);
		
		FireballLvl = 0;
		HealLvl = 0;
		BurstballLvl = 0;
		FirewalkLvl = 0;
		FirearmorLvl = 0;
		FirespikeLvl = 0;
		MayaS = GetComponent<MayaMove>();
		SpellsCanvas.alpha = 0f;
	}
	
	void Update () {
		if (Input.GetKeyDown("n")){
			Show();
		}

		if (Input.GetKeyDown(KeyCode.Alpha1))
		{
			spell1.Invoke();
		}
		if (Input.GetKeyDown(KeyCode.Alpha2))
		{
			spell2.Invoke();
		}
		if (Input.GetKeyDown(KeyCode.Alpha3))
		{
			spell3.Invoke();
		}
		if (Input.GetKeyDown(KeyCode.Alpha4))
		{
			spell4.Invoke();
		}

		if (SpellsCanvas.alpha == 1f){
			UpdateValues();
		}
	}

	public void Show(){
		if (SpellsCanvas.alpha == 0f){
			SpellsCanvas.alpha = 1f;
			GameHUD.alpha = 0f;
			EnemyCanvas.alpha = 0f;
		}
		else{
			SpellsCanvas.alpha = 0f;
			GameHUD.alpha = 1f;
		}
	}

	void UpdateValues(){
		if (MayaS.SpellPoints > 0){
			if (MayaS.Level >= 2 && FireballLvl <= 5){
				FireballPlus.interactable = true;
			}
			else {
				FireballPlus.interactable = false;
			}

			if (MayaS.Level >= 2 && HealLvl <= 5){
				HealPlus.interactable = true;
			}
			else {
				HealPlus.interactable = false;
			}
			if (MayaS.Level >= 6 && FirespikeLvl <= 5){
				FirespikePlus.interactable = true;
			}
			else {
				FirespikePlus.interactable = false;
			}
			if (MayaS.Level >= 12 && FireballLvl > 0){
				BurstballPlus.interactable = true;
			}
			else {
				BurstballPlus.interactable = false;
			}
			if (MayaS.Level >= 12 && FirespikeLvl > 0 && FirewalkLvl <= 5){
				FirewalkPlus.interactable = true;
			}
			else {
				FirewalkPlus.interactable = false;
			}
			if (MayaS.Level >= 16 && FirewalkLvl > 0){
				FirearmorPlus.interactable = true;
			}
			else {
				FirearmorPlus.interactable = false;
			}
		}
		else {
			FireballPlus.interactable = false;
			HealPlus.interactable = false;
			FirespikePlus.interactable = false;
			BurstballPlus.interactable = false;
			FirewalkPlus.interactable = false;
			FirearmorPlus.interactable = false;
		}

		FireballValue.text = FireballLvl.ToString();
		HealValue.text = HealLvl.ToString();
		BurstballValue.text = BurstballLvl.ToString();
		FirewalkValue.text = FirewalkLvl.ToString();
		FirearmorValue.text = FirearmorLvl.ToString();
		FirespikeValue.text = FirespikeLvl.ToString();
	}
	public bool boostedFireball = false;
	public void boostFireBall(){
		if (FireballLvl == 0)
			fireball.gameObject.SetActive(true);
        FireballLvl += 1;
		if (boostedFireball)
			fireball.damage = 10 * FireballLvl + 15* BurstballLvl;
		else
			fireball.damage = 10 * FireballLvl;
        MayaS.SpellPoints--;
    }

    public void boostHeal(){
		if (HealLvl == 0)
			healSpell.gameObject.SetActive(true);
        HealLvl += 1;
		healSpell.healAmount = 5 + 5 * HealLvl;
        MayaS.SpellPoints--;
    }

    public void boostBurstBall(){
        BurstballLvl += 1;
        MayaS.SpellPoints--;
    }

    public void boostFireWalk(){
        FirewalkLvl += 1;
        MayaS.SpellPoints--;
    }

    public void boostFireArmor(){
		if (FireballLvl == 0)
			fireShield.gameObject.SetActive(true);
        FirearmorLvl += 1;
		fireShield.damage = 5 * FirearmorLvl;
        MayaS.SpellPoints--;
    }

    public void boostFireSpike(){
		if (FirespikeLvl == 0)
			aoeSpell.gameObject.SetActive(true);
        FirespikeLvl += 1;
		aoeSpell.damage = 100 * FirespikeLvl;
        MayaS.SpellPoints--;
    }
}
